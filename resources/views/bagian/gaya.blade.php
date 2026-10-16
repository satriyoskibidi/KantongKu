<style>
    :root {
        --kk-forest: #123d32;
        --kk-green: #167a5a;
        --kk-green-dark: #0f6248;
        --kk-mint: #e7f4ed;
        --kk-ink: #1d3029;
        --kk-muted: #728078;
        --kk-line: #e3eae5;
        --kk-paper: #f4f7f4;
        --kk-orange: #de8150;
        --bs-primary: var(--kk-green);
        --bs-primary-rgb: 22, 122, 90;
        --bs-link-color: var(--kk-green);
        --bs-link-hover-color: var(--kk-green-dark);
    }
    body { background: var(--kk-paper); color: var(--kk-ink); font-family: 'DM Sans', sans-serif; min-height: 100vh; }
    .navbar-kk { background: var(--kk-forest); }
    .navbar-kk .navbar-brand { font-size: 1.12rem; letter-spacing: 0; }
    .navbar-kk .nav-link { color: rgba(255,255,255,.75); border-radius: 6px; padding: .55rem .75rem; }
    .navbar-kk .nav-link:hover, .navbar-kk .nav-link.active { color: #fff; background: rgba(255,255,255,.1); }
    .page-shell { max-width: 1240px; }
    .page-heading { display: flex; align-items: end; justify-content: space-between; gap: 1rem; margin-bottom: 1.5rem; }
    .page-heading h1 { color: var(--kk-ink); font-size: 1.65rem; font-weight: 700; margin: 0; }
    .eyebrow { color: var(--kk-green); font-size: .75rem; font-weight: 700; letter-spacing: 0; text-transform: uppercase; }
    .surface { background: #fff; border: 1px solid var(--kk-line); border-radius: 8px; }
    .stat-card { min-height: 132px; padding: 1.15rem; border-top: 3px solid var(--kk-green); }
    .stat-card.expense { border-top-color: var(--kk-orange); }
    .stat-card.balance { border-top-color: #416c9c; }
    .stat-card.rate { border-top-color: #9a7746; }
    .stat-label { color: var(--kk-muted); font-size: .85rem; font-weight: 600; }
    .stat-value { color: var(--kk-ink); font-size: 1.55rem; font-weight: 700; line-height: 1.2; overflow-wrap: anywhere; }
    .stat-icon { align-items: center; background: var(--kk-mint); border-radius: 8px; color: var(--kk-green); display: inline-flex; font-size: 1.1rem; height: 38px; justify-content: center; width: 38px; }
    .stat-card.expense .stat-icon { background: #fff0e8; color: var(--kk-orange); }
    .stat-card.balance .stat-icon { background: #edf2f8; color: #416c9c; }
    .stat-card.rate .stat-icon { background: #f6f0e5; color: #9a7746; }
    .panel-title { font-size: 1rem; font-weight: 700; }
    .form-control, .form-select { border-color: #d6e0d9; min-height: 42px; }
    .form-control:focus, .form-select:focus { border-color: var(--kk-green); box-shadow: 0 0 0 .2rem rgba(22,122,90,.14); }
    .btn-primary { --bs-btn-bg: var(--kk-green); --bs-btn-border-color: var(--kk-green); --bs-btn-hover-bg: var(--kk-green-dark); --bs-btn-hover-border-color: var(--kk-green-dark); --bs-btn-active-bg: var(--kk-green-dark); --bs-btn-active-border-color: var(--kk-green-dark); }
    .btn-outline-primary { --bs-btn-color: var(--kk-green); --bs-btn-border-color: var(--kk-green); --bs-btn-hover-bg: var(--kk-green); --bs-btn-hover-border-color: var(--kk-green); }
    .text-primary { color: var(--kk-green) !important; }
    .table { --bs-table-bg: transparent; --bs-table-color: var(--kk-ink); margin-bottom: 0; }
    .table thead th { border-bottom-color: var(--kk-line); color: var(--kk-muted); font-size: .73rem; font-weight: 700; padding: .8rem 1rem; text-transform: uppercase; }
    .table tbody td { border-bottom-color: #edf1ee; padding: .9rem 1rem; vertical-align: middle; }
    .table tbody tr:last-child td { border-bottom: 0; }
    .table-responsive { border-radius: 8px; }
    .badge-soft { background: var(--kk-mint); color: var(--kk-green-dark); font-weight: 600; }
    .insight-band { background: var(--kk-forest); border-radius: 8px; color: #fff; }
    .insight-band .insight-symbol { align-items: center; background: rgba(255,255,255,.12); border-radius: 8px; display: inline-flex; flex: 0 0 40px; height: 40px; justify-content: center; }
    .auth-wrap { align-items: center; background: var(--kk-forest); display: flex; min-height: 100vh; padding: 2rem 1rem; }
    .auth-panel { background: #fff; border-radius: 10px; margin: auto; max-width: 1000px; overflow: hidden; width: 100%; }
    .auth-aside { background: var(--kk-forest); color: #fff; min-height: 480px; padding: 3rem; position: relative; }
    .auth-aside:after { border: 1px solid rgba(255,255,255,.2); border-radius: 50%; content: ''; height: 330px; position: absolute; right: -125px; top: 210px; width: 330px; }
    .auth-aside:before { border: 1px solid rgba(255,255,255,.15); border-radius: 50%; content: ''; height: 230px; position: absolute; right: -75px; top: 260px; width: 230px; }
    .auth-aside-content { position: relative; z-index: 1; }
    .auth-aside h1 { font-size: 2.1rem; font-weight: 700; max-width: 360px; }
    .auth-main { padding: clamp(2rem, 5vw, 4.5rem); }
    .auth-main h2 { font-size: 1.55rem; font-weight: 700; }
    .empty-state { color: var(--kk-muted); padding: 3.5rem 1rem; text-align: center; }
    .empty-state i { color: #9aada1; font-size: 2rem; }
    .footer-kk { color: var(--kk-muted); font-size: .8rem; }
    @media (max-width: 767.98px) {
        .page-heading { align-items: start; flex-direction: column; }
        .page-heading h1 { font-size: 1.4rem; }
        .stat-value { font-size: 1.35rem; }
        .auth-aside { min-height: auto; padding: 2rem; }
        .auth-aside h1 { font-size: 1.6rem; }
        .auth-aside:before, .auth-aside:after { display: none; }
    }
</style>