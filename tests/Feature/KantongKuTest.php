<?php

namespace Tests\Feature;

use App\Models\Pengeluaran;
use App\Models\Pemasukan;
use App\Models\Pengguna;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class KantongKuTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_log_in_and_view_the_dashboard(): void
    {
        $student = Pengguna::factory()->create(['email' => 'student@example.test', 'password' => 'password123']);

        $this->post(route('login.store'), [
            'email' => $student->email,
            'password' => 'password123',
        ])->assertRedirectToRoute('dashboard');

        $this->get(route('dashboard'))->assertOk()->assertSee('Arus uang harian');
    }

    public function test_inactive_account_cannot_log_in_or_continue_a_session(): void
    {
        $student = Pengguna::factory()->create([
            'email' => 'inactive@example.test',
            'password' => 'password123',
            'is_active' => false,
        ]);

        $this->from(route('login'))->post(route('login.store'), [
            'email' => $student->email,
            'password' => 'password123',
        ])->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');
        $this->assertGuest();

        $this->actingAs($student)->get(route('dashboard'))
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_student_can_register_and_cannot_choose_an_admin_role(): void
    {
        $this->get(route('register'))->assertOk()->assertSee('Buat akun baru');

        $this->post(route('register.store'), [
            'name' => 'Pendaftar Baru',
            'email' => 'pendaftar@example.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin',
        ])->assertRedirectToRoute('dashboard');

        $student = Pengguna::where('email', 'pendaftar@example.test')->firstOrFail();
        $this->assertSame('mahasiswa', $student->role);
        $this->assertAuthenticatedAs($student);
        $this->assertNotSame('password123', $student->password);
    }

    public function test_registration_rejects_duplicate_email_and_unconfirmed_password(): void
    {
        Pengguna::factory()->create(['email' => 'existing@example.test']);

        $this->from(route('register'))->post(route('register.store'), [
            'name' => 'Pendaftar Baru',
            'email' => 'existing@example.test',
            'password' => 'password123',
            'password_confirmation' => 'different123',
        ])->assertRedirect(route('register'))
            ->assertSessionHasErrors(['email', 'password']);
    }

    public function test_student_can_create_own_transactions_but_cannot_access_others(): void
    {
        $student = Pengguna::factory()->create();
        $otherStudent = Pengguna::factory()->create();
        $this->actingAs($student);

        $this->post(route('incomes.store'), [
            'title' => 'Beasiswa',
            'category' => 'Pendidikan',
            'amount' => '1500000.00',
            'transaction_date' => now()->toDateString(),
        ])->assertRedirectToRoute('incomes.index');

        $income = Pemasukan::where('title', 'Beasiswa')->firstOrFail();
        $this->assertSame($student->id, $income->user_id);
        $this->get(route('incomes.edit', $income))->assertOk();

        $otherIncome = Pemasukan::create([
            'user_id' => $otherStudent->id,
            'title' => 'Privat',
            'category' => 'Pekerjaan',
            'amount' => 250000,
            'transaction_date' => now()->toDateString(),
        ]);
        $this->get(route('incomes.edit', $otherIncome))->assertNotFound();
        $this->delete(route('incomes.destroy', $otherIncome))->assertNotFound();

        $this->post(route('expenses.store'), [
            'title' => 'Makan siang',
            'category' => 'Makan & minum',
            'amount' => '25000',
            'transaction_date' => now()->toDateString(),
        ])->assertRedirectToRoute('expenses.index');

        $this->assertDatabaseHas('expenses', ['user_id' => $student->id, 'title' => 'Makan siang']);
    }

    public function test_student_cannot_assign_transaction_to_another_account_or_open_admin_pages(): void
    {
        $student = Pengguna::factory()->create();
        $otherStudent = Pengguna::factory()->create();
        $this->actingAs($student);

        $this->post(route('incomes.store'), [
            'user_id' => $otherStudent->id,
            'title' => 'Tidak sah',
            'category' => 'Lainnya',
            'amount' => 10000,
            'transaction_date' => now()->toDateString(),
        ])->assertSessionHasErrors('user_id');

        $this->get(route('users.index'))->assertForbidden();
    }

    public function test_admin_can_record_for_a_student_and_manage_student_accounts(): void
    {
        $admin = Pengguna::factory()->create(['role' => 'admin']);
        $student = Pengguna::factory()->create();
        $this->actingAs($admin);

        $this->post(route('incomes.store'), [
            'user_id' => $student->id,
            'title' => 'Uang saku',
            'category' => 'Uang saku',
            'amount' => 500000,
            'transaction_date' => now()->toDateString(),
        ])->assertRedirectToRoute('incomes.index');

        $this->assertDatabaseHas('incomes', ['user_id' => $student->id, 'title' => 'Uang saku']);
        $this->get(route('users.index'))->assertOk()->assertSee($student->name);

        $this->post(route('users.store'), [
            'name' => 'Mahasiswa Baru',
            'email' => 'baru@example.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirectToRoute('users.index');

        $this->assertDatabaseHas('users', ['email' => 'baru@example.test', 'role' => 'mahasiswa']);
    }

    public function test_admin_has_a_separate_dashboard_and_read_only_transaction_monitor(): void
    {
        $admin = Pengguna::factory()->create(['role' => 'admin']);
        $student = Pengguna::factory()->create();
        Pemasukan::create([
            'user_id' => $student->id,
            'title' => 'Beasiswa bulan ini',
            'category' => 'Beasiswa',
            'amount' => 1000000,
            'transaction_date' => now()->startOfMonth()->toDateString(),
        ]);
        Pengeluaran::create([
            'user_id' => $student->id,
            'title' => 'Makan bulan ini',
            'category' => 'Makan',
            'amount' => 200000,
            'transaction_date' => now()->startOfMonth()->toDateString(),
        ]);

        $this->actingAs($admin)->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Dashboard admin')
            ->assertSee('Total mahasiswa')
            ->assertDontSee('Rasio tabungan');

        $this->get(route('admin.transactions.index'))
            ->assertOk()
            ->assertSee($student->name)
            ->assertSee('Beasiswa bulan ini')
            ->assertSee('Makan bulan ini')
            ->assertSee('hanya-baca');

        $this->get(route('admin.transactions.index', [
            'user_id' => $student->id,
            'from' => now()->startOfMonth()->toDateString(),
            'to' => now()->startOfMonth()->toDateString(),
            'category' => 'Beasiswa',
            'type' => 'income',
        ]))->assertOk()->assertSee('Beasiswa bulan ini')->assertDontSee('Makan bulan ini');

        $this->get(route('admin.reports.index', ['month' => now()->format('Y-m')]))
            ->assertOk()
            ->assertSee('Rp 1.000.000')
            ->assertSee('Rp 200.000')
            ->assertSee('Pengeluaran per kategori');
    }

    public function test_admin_can_manage_categories_student_status_and_own_profile(): void
    {
        $admin = Pengguna::factory()->create(['role' => 'admin', 'password' => 'password123']);
        $student = Pengguna::factory()->create();
        $this->actingAs($admin);

        $this->post(route('categories.store'), ['name' => 'Kuota', 'type' => 'expense'])
            ->assertRedirectToRoute('categories.index');
        $this->assertDatabaseHas('categories', ['name' => 'Kuota', 'type' => 'expense']);
        $category = \App\Models\Kategori::where('name', 'Kuota')->firstOrFail();
        $this->put(route('categories.update', $category), ['name' => 'Kuota internet', 'type' => 'expense'])
            ->assertRedirectToRoute('categories.index');
        $this->delete(route('categories.destroy', $category->fresh()))->assertRedirectToRoute('categories.index');
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);

        $this->put(route('users.update', $student), [
            'name' => 'Mahasiswa diperbarui',
            'email' => $student->email,
            'password' => 'reset123',
            'password_confirmation' => 'reset123',
        ])->assertRedirectToRoute('users.index');
        $this->assertTrue(Hash::check('reset123', $student->fresh()->password));

        $this->patch(route('users.status', $student))->assertRedirectToRoute('users.index');
        $this->assertDatabaseHas('users', ['id' => $student->id, 'is_active' => false]);

        $this->put(route('admin.profile.update'), [
            'name' => 'Admin Baru',
            'current_password' => 'password123',
            'password' => 'password456',
            'password_confirmation' => 'password456',
        ])->assertRedirectToRoute('admin.profile.edit');

        $this->assertDatabaseHas('users', ['id' => $admin->id, 'name' => 'Admin Baru']);
        $this->assertTrue(Hash::check('password456', $admin->fresh()->password));
    }

    public function test_student_cannot_access_admin_monitoring_reports_categories_or_profile(): void
    {
        $student = Pengguna::factory()->create();
        $this->actingAs($student);

        $this->get(route('admin.transactions.index'))->assertForbidden();
        $this->get(route('admin.reports.index'))->assertForbidden();
        $this->get(route('categories.index'))->assertForbidden();
        $this->get(route('admin.profile.edit'))->assertForbidden();
    }

    public function test_dashboard_shows_monthly_insight_and_totals(): void
    {
        $student = Pengguna::factory()->create();
        Pemasukan::create([
            'user_id' => $student->id,
            'title' => 'Beasiswa',
            'category' => 'Pendidikan',
            'amount' => 1000000,
            'transaction_date' => now()->startOfMonth()->toDateString(),
        ]);
        Pengeluaran::create([
            'user_id' => $student->id,
            'title' => 'Buku',
            'category' => 'Kuliah',
            'amount' => 100000,
            'transaction_date' => now()->startOfMonth()->toDateString(),
        ]);

        $this->actingAs($student)->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Rp 1.000.000')
            ->assertSee('Rp 100.000')
            ->assertSee('Rasio tabungan');
    }

    public function test_account_with_transaction_history_cannot_be_deleted(): void
    {
        $admin = Pengguna::factory()->create(['role' => 'admin']);
        $student = Pengguna::factory()->create();
        Pemasukan::create([
            'user_id' => $student->id,
            'title' => 'Beasiswa',
            'category' => 'Pendidikan',
            'amount' => 1000,
            'transaction_date' => now()->toDateString(),
        ]);

        $this->actingAs($admin)->delete(route('users.destroy', $student))
            ->assertRedirect();
        $this->assertDatabaseHas('users', ['id' => $student->id]);
    }
}
