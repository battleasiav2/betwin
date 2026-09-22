@extends($activeTemplate . 'layouts.master')
@section('content')

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

<style>
    :root {
        --bg-color: #e8f0fa;
        --header-color: #154b77;
        --accent-color: #43a047;
        --card-bg: #ffffff;
        --text-main: #333333;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
        --blue-light: #e0f2fe;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    html, body {
        max-width: 100vw;
        overflow-x: hidden;
        touch-action: manipulation;
    }

    body { 
        background-color: var(--bg-color); 
        color: var(--text-main); 
        font-family: 'Roboto', sans-serif; 
        padding-bottom: 90px; 
        -webkit-tap-highlight-color: transparent;
        user-select: none;
    }
    input { user-select: auto; }

    .password-page-wrapper {
        min-height: 100vh;
    }

    /* â”€â”€â”€ HEADER â”€â”€â”€ */
    .header-bg { 
        background: linear-gradient(135deg, #0f395c 0%, #1a5c92 100%);
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding: 16px;
        display: flex;
        align-items: center;
        position: sticky;
        top: 0;
        z-index: 50;
        gap: 16px;
    }
    .header-bg a {
        color: #ffffff;
        font-size: 20px;
        padding: 4px;
        text-decoration: none;
    }
    .header-bg h1 {
        color: #ffffff;
        font-weight: 700;
        font-size: 15px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* â”€â”€â”€ SECURITY SCORE CARD â”€â”€â”€ */
    .security-card {
        background: var(--card-bg);
        border-radius: 12px;
        padding: 24px;
        margin: 16px;
        display: flex;
        align-items: center;
        gap: 24px;
        border: 1px solid var(--border-color);
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }
    .security-ring {
        position: relative;
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .security-ring .ring-bg {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        border: 4px solid #bfdbfe;
        border-top-color: #3b82f6;
        animation: spin 5s linear infinite;
        position: absolute;
    }
    @keyframes spin {
        100% { transform: rotate(360deg); }
    }
    .security-percent {
        font-size: 20px;
        font-weight: 700;
        color: #2563eb;
        z-index: 1;
    }
    .security-status {
        font-weight: 700;
        color: #333;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }
    .security-status i {
        color: #eab308;
    }
    .security-last-login {
        font-size: 10px;
        color: #9ca3af;
        margin-top: 4px;
        font-weight: 500;
    }

    /* â”€â”€â”€ WARNING TEXT â”€â”€â”€ */
    .warning-text {
        text-align: center;
        font-size: 11px;
        color: #ef4444;
        margin: 0 16px 20px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-style: italic;
    }

    /* â”€â”€â”€ MENU LIST â”€â”€â”€ */
    .menu-list {
        background: var(--card-bg);
        margin: 0 16px;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        overflow: hidden;
    }
    .menu-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px;
        text-decoration: none;
        transition: background 0.2s;
        border-bottom: 1px solid var(--border-color);
    }
    .menu-row:last-child { border-bottom: none; }
    .menu-row:active { background: #f9fafb; }
    .menu-row.logout-row:active { background: #fef2f2; }

    .menu-row-left {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .menu-icon-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }
    .menu-icon-circle.blue { background: #eff6ff; color: #3b82f6; }
    .menu-icon-circle.green { background: #f0fdf4; color: #22c55e; }
    .menu-icon-circle.purple { background: #faf5ff; color: #a855f7; }
    .menu-icon-circle.red { background: #fef2f2; color: #ef4444; }

    .menu-row-title {
        font-weight: 700;
        color: #333;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .menu-row-title i.status-icon {
        font-size: 10px;
    }
    .menu-row-title i.status-ok { color: #22c55e; }
    .menu-row-title i.status-warn { color: #ef4444; }
    .menu-row-sub {
        font-size: 11px;
        color: #9ca3af;
        font-weight: 500;
        margin-top: 2px;
    }
    .menu-arrow {
        color: #d1d5db;
        font-size: 14px;
    }

    /* â”€â”€â”€ CHANGE PASSWORD FORM â”€â”€â”€ */
    .form-card {
        background: var(--card-bg);
        border-radius: 12px;
        padding: 24px;
        margin: 16px;
        border: 1px solid var(--border-color);
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }
    .form-group {
        margin-bottom: 16px;
    }
    .form-label {
        font-size: 12px;
        font-weight: 700;
        color: var(--header-color);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        display: block;
    }
    .form-input {
        width: 100%;
        background: #f8fafc;
        border: 1.5px solid var(--border-color);
        color: #333;
        border-radius: 8px;
        padding: 14px 16px;
        font-size: 14px;
        font-weight: 600;
        outline: none;
        transition: 0.2s;
    }
    .form-input:focus {
        border-color: var(--header-color);
        box-shadow: 0 0 0 3px rgba(21, 75, 119, 0.1);
    }
    .submit-btn {
        width: 100%;
        background: linear-gradient(to bottom, #4caf50, #388e3c);
        color: #ffffff;
        font-weight: 800;
        font-size: 14px;
        padding: 14px;
        border-radius: 8px;
        border: none;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        cursor: pointer;
        box-shadow: 0 4px 6px rgba(67, 160, 71, 0.2);
        transition: 0.2s;
    }
    .submit-btn:active {
        transform: translateY(2px);
        box-shadow: 0 2px 4px rgba(67, 160, 71, 0.2);
    }

    .tab-content {
        display: none;
        animation: fadeIn 0.3s ease;
    }
    .tab-content.active {
        display: block;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* â”€â”€â”€ TOGGLE SWITCH â”€â”€â”€ */
    .toggle-wrapper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 0;
    }
    .toggle-label {
        font-weight: 700;
        color: #333;
        font-size: 14px;
    }
    .toggle-switch {
        position: relative;
        width: 48px;
        height: 26px;
        flex-shrink: 0;
    }
    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #d1d5db;
        transition: 0.3s;
        border-radius: 26px;
    }
    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: 0.3s;
        border-radius: 50%;
    }
    .toggle-switch input:checked + .toggle-slider {
        background-color: #22c55e;
    }
    .toggle-switch input:checked + .toggle-slider:before {
        transform: translateX(22px);
    }

    /* â”€â”€â”€ BOTTOM NAV â”€â”€â”€ */
    .bottom-nav-container {
        position: fixed;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100%;
        max-width: 480px;
        z-index: 10000;
        padding: 0 10px 8px 10px;
        background: #e8f0fa;
    }

    .bottom-nav {
        width: 100%;
        height: 58px;
        background: linear-gradient(180deg, #0e3d2c 0%, #0a2d1f 100%);
        border-radius: 999px;
        border: 1.5px solid #1a5c40;
        box-shadow:
            0 0 0 2px #e8f0fa,
            inset 0 1px 0 rgba(37,99,235,0.12),
            0 -2px 0 0 #2563eb,
            0 4px 24px rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: space-around;
        padding: 0 6px;
        position: relative;
    }

    .nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 3px;
        flex: 1;
        text-decoration: none !important;
        color: #3db88a;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.2px;
        padding: 6px 0;
        transition: color 0.2s;
        position: relative;
    }

    .nav-item.active { color: #f5c518; }
    .nav-item.active span { border-bottom: 2px solid #f5c518; padding-bottom: 1px; }

    .nav-item i { font-size: 20px; }
    .nav-item span { font-size: 10px; font-weight: 700; }

    .center-item {
        position: relative;
        flex: 1;
        justify-content: flex-end;
        padding-bottom: 0;
    }

    .center-icon-circle {
        width: 54px;
        height: 54px;
        border-radius: 50%;
        background: linear-gradient(145deg, #2563eb, #123b66);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow:
            0 0 0 3px #e8f0fa,
            0 0 0 5px #2563eb,
            0 6px 20px rgba(37,99,235,0.45);
        font-size: 22px;
        color: #fff;
        margin-top: -18px;
        border: none;
    }

    @media (min-width: 900px) {
        .bottom-nav-container { max-width: 600px; }
    }
</style>

<div class="password-page-wrapper">

    <!-- HEADER -->
    <div class="header-bg">
        <a href="{{ route('user.account') }}"><i class="fas fa-chevron-left"></i></a>
        <span class="page-accent-icon navy"><i class="fas fa-shield-halved"></i></span>
        <h1>@lang('Security Center')</h1>
    </div>

    <!-- SECURITY SCORE -->
    <div class="security-card">
        <div class="security-ring">
            <div class="ring-bg"></div>
            <span class="security-percent">66%</span>
        </div>
        <div>
            <div class="security-status">
                Security: Medium <i class="fas fa-bolt"></i>
            </div>
            <div class="security-last-login">
                Last login: {{ showDateTime(auth()->user()->last_login ?? now(), 'Y-m-d H:i') }}
            </div>
        </div>
    </div>

    <p class="warning-text">
        Your account security level is medium, please improve your info.
    </p>

    <!-- MENU LIST -->
    <div class="menu-list">
        <!-- Personal Info -->
        <a href="{{ route('user.profile.setting') }}" class="menu-row">
            <div class="menu-row-left">
                <div class="menu-icon-circle blue">
                    <i class="far fa-user"></i>
                </div>
                <div>
                    <div class="menu-row-title">
                        Personal Info
                        <i class="fas fa-exclamation-circle status-warn status-icon"></i>
                    </div>
                    <div class="menu-row-sub">Complete your personal details</div>
                </div>
            </div>
            <i class="fas fa-chevron-right menu-arrow"></i>
        </a>

        <!-- Bind Wallet -->
        <a href="{{ route('user.withdraw') }}" class="menu-row">
            <div class="menu-row-left">
                <div class="menu-icon-circle green">
                    <i class="fas fa-wallet"></i>
                </div>
                <div>
                    <div class="menu-row-title">
                        Bind Wallet
                        <i class="fas fa-check-circle status-ok status-icon"></i>
                    </div>
                    <div class="menu-row-sub">Add payment details for withdrawals</div>
                </div>
            </div>
            <i class="fas fa-chevron-right menu-arrow"></i>
        </a>

        <!-- Change Password (Expandable) -->
        <div class="menu-row" onclick="togglePasswordForm()" style="cursor: pointer;">
            <div class="menu-row-left">
                <div class="menu-icon-circle purple">
                    <i class="fas fa-lock"></i>
                </div>
                <div>
                    <div class="menu-row-title">
                        Change Password
                        <i class="fas fa-check-circle status-ok status-icon"></i>
                    </div>
                    <div class="menu-row-sub">Secure your login credentials</div>
                </div>
            </div>
            <i class="fas fa-chevron-down menu-arrow" id="passwordToggleIcon"></i>
        </div>

        <!-- Password Form (Hidden by default) -->
        <div id="passwordForm" class="tab-content" style="padding: 0 20px 20px;">
            <form method="post" action="{{ route('user.change.password') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Current Password</label>
                    <input type="password" name="current_password" class="form-input" required autocomplete="current-password" placeholder="Enter current password">
                </div>
                <div class="form-group">
                    <label class="form-label">New Password</label>
                    <input type="password" name="password" class="form-input @if(gs('secure_password')) secure-password @endif" required autocomplete="new-password" placeholder="Enter new password">
                </div>
                <div class="form-group">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-input" required autocomplete="new-password" placeholder="Confirm new password">
                </div>
                <button type="submit" class="submit-btn">@lang('Update Password')</button>
            </form>
        </div>

        <!-- 2FA Toggle -->
        <div class="menu-row" onclick="window.location.href='{{ route('user.twofactor') }}'" style="cursor: pointer;">
            <div class="menu-row-left">
                <div class="menu-icon-circle blue">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div>
                    <div class="menu-row-title">
                        Two-Factor Authentication (2FA)
                    </div>
                    <div class="menu-row-sub">Add extra security layer</div>
                </div>
            </div>
            <div class="toggle-wrapper" style="padding: 0;" onclick="event.stopPropagation();">
                <label class="toggle-switch">
                    <input type="checkbox" {{ auth()->user()->ts ? 'checked' : '' }} onchange="window.location.href='{{ route('user.twofactor') }}'">
                    <span class="toggle-slider"></span>
                </label>
            </div>
        </div>

        <!-- Logout -->
        <a href="{{ route('user.logout') }}" class="menu-row logout-row">
            <div class="menu-row-left">
                <div class="menu-icon-circle red">
                    <i class="fas fa-power-off"></i>
                </div>
                <div>
                    <div class="menu-row-title">Logout</div>
                    <div class="menu-row-sub">Securely exit your session</div>
                </div>
            </div>
            <i class="fas fa-chevron-right menu-arrow"></i>
        </a>
    </div>

</div>

@include($activeTemplate . 'partials.mobile_bottom_nav')

<script>
    function togglePasswordForm() {
        const form = document.getElementById('passwordForm');
        const icon = document.getElementById('passwordToggleIcon');
        form.classList.toggle('active');
        if (form.classList.contains('active')) {
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-up');
        } else {
            icon.classList.remove('fa-chevron-up');
            icon.classList.add('fa-chevron-down');
        }
    }
</script>

@if(gs('secure_password'))
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush
@endif

@endsection