<?php

namespace Tests\Feature;

use App\Models\Expense;
use App\Models\Income;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KantongKuTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_log_in_and_view_the_dashboard(): void
    {
        $student = User::factory()->create(['email' => 'student@example.test', 'password' => 'password123']);

        $this->post(route('login.store'), [
            'email' => $student->email,
            'password' => 'password123',
        ])->assertRedirectToRoute('dashboard');

        $this->get(route('dashboard'))->assertOk()->assertSee('Arus uang harian');
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

        $student = User::where('email', 'pendaftar@example.test')->firstOrFail();
        $this->assertSame('mahasiswa', $student->role);
        $this->assertAuthenticatedAs($student);
        $this->assertNotSame('password123', $student->password);
    }

    public function test_registration_rejects_duplicate_email_and_unconfirmed_password(): void
    {
        User::factory()->create(['email' => 'existing@example.test']);

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
        $student = User::factory()->create();
        $otherStudent = User::factory()->create();
        $this->actingAs($student);

        $this->post(route('incomes.store'), [
            'title' => 'Beasiswa',
            'category' => 'Pendidikan',
            'amount' => '1500000.00',
            'transaction_date' => now()->toDateString(),
        ])->assertRedirectToRoute('incomes.index');

        $income = Income::where('title', 'Beasiswa')->firstOrFail();
        $this->assertSame($student->id, $income->user_id);
        $this->get(route('incomes.edit', $income))->assertOk();

        $otherIncome = Income::create([
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
        $student = User::factory()->create();
        $otherStudent = User::factory()->create();
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
        $admin = User::factory()->create(['role' => 'admin']);
        $student = User::factory()->create();
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

    public function test_dashboard_shows_monthly_insight_and_totals(): void
    {
        $student = User::factory()->create();
        Income::create([
            'user_id' => $student->id,
            'title' => 'Beasiswa',
            'category' => 'Pendidikan',
            'amount' => 1000000,
            'transaction_date' => now()->startOfMonth()->toDateString(),
        ]);
        Expense::create([
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
        $admin = User::factory()->create(['role' => 'admin']);
        $student = User::factory()->create();
        Income::create([
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