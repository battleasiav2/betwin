@extends($activeTemplate . 'layouts.master')
@section('content')

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        background-color: #e8f0fa !important;
        color: #172033;
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        margin: 0;
        padding-bottom: 80px;
        -webkit-tap-highlight-color: transparent;
    }

    .profile-page-wrapper {
        min-height: 100vh;
        background: transparent;
    }

    /* ─── HEADER ─── */
    .header-bg {
        background: linear-gradient(180deg, #123b66 0%, #1a4f86 55%, #2563eb 100%);
        padding: 20px 15px 70px 15px;
        text-align: center;
    }
    .header-top-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
    }
    .header-top-row a {
        color: #ffffff;
        font-size: 20px;
        text-decoration: none;
    }
    .header-top-row .header-title {
        font-weight: 700;
        font-size: 16px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #ffffff;
    }
    .header-top-row .spacer {
        width: 24px;
    }

    /* ─── USER CARD ─── */
    .user-card {
        background: linear-gradient(145deg, #ffffff 0%, #f5f7fa 100%);
        border-radius: 22px;
        margin: -50px 15px 20px 15px;
        padding: 20px;
        position: relative;
        box-shadow: 0 16px 36px rgba(18, 59, 102, 0.14);
        border: 1px solid #e8f0fa;
        color: #172033;
    }

    .sign-in-tag {
        position: absolute;
        top: 0;
        right: 0;
        background: #2563eb;
        color: white;
        padding: 5px 15px;
        border-top-right-radius: 20px;
        border-bottom-left-radius: 20px;
        font-size: 11px;
        font-weight: bold;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .sign-in-tag i {
        font-size: 12px;
        color: #f4b942;
    }

    .avatar-box {
        width: 85px;
        height: 85px;
        border-radius: 50%;
        border: 4px solid #ffffff;
        overflow: hidden;
        box-shadow: 0 8px 20px rgba(18, 59, 102, 0.16);
        flex-shrink: 0;
        background: #e8f0fa;
    }
    .avatar-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .vip-tag {
        background: #123b66;
        color: #f4b942;
        font-size: 10px;
        padding: 2px 10px;
        border-radius: 10px;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        width: fit-content;
    }

    .action-btn {
        background: #e8f0fa;
        border-radius: 25px;
        padding: 10px;
        font-size: 13px;
        font-weight: bold;
        color: #123b66;
        flex: 1;
        text-align: center;
        box-shadow: none;
        border: 1px solid #d5e4f7;
        text-decoration: none;
        display: inline-block;
        transition: all 0.15s;
    }
    .action-btn:first-child {
        background: #2563eb;
        color: #ffffff;
        border-color: #2563eb;
        box-shadow: 0 8px 16px rgba(37, 99, 235, 0.25);
    }
    .action-btn:nth-child(2) {
        background: #123b66;
        color: #ffffff;
        border-color: #123b66;
    }
    .action-btn:active {
        transform: scale(0.95);
        opacity: 0.92;
    }

    /* ─── MENU CONTAINER ─── */
    .menu-container {
        background: rgba(255, 255, 255, 0.94) !important;
        border-radius: 28px 28px 0 0;
        padding-top: 20px;
        margin-top: 10px;
        border: 1px solid #e8f0fa !important;
        box-shadow: 0 -8px 28px rgba(18, 59, 102, 0.06) !important;
    }

    .section-title {
        color: #123b66;
        font-size: 13px;
        font-weight: 800;
        padding-left: 20px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
    }
    .section-title::after {
        content: "";
        height: 1px;
        flex: 1;
        background: #d5e4f7;
        margin-left: 10px;
    }

    .menu-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 22px 8px;
        padding: 18px 12px 28px;
    }

    .menu-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-decoration: none;
        position: relative;
        transition: all 0.15s;
    }
    .menu-item:active {
        transform: scale(0.92);
    }

    .icon-box {
        width: 54px;
        height: 54px;
        background: linear-gradient(145deg, #e8f0fa 0%, #ffffff 100%);
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 8px;
        color: #2563eb;
        font-size: 20px;
        border: 1px solid #d5e4f7;
        box-shadow: 0 8px 16px rgba(18, 59, 102, 0.08);
    }

    .menu-item:nth-child(4n+1) .icon-box { color: #2563eb; }
    .menu-item:nth-child(4n+2) .icon-box { color: #123b66; }
    .menu-item:nth-child(4n+3) .icon-box { color: #d9a12a; }
    .menu-item:nth-child(4n) .icon-box { color: #2563eb; }

    .menu-label {
        font-size: 10px;
        color: #6b7280;
        text-align: center;
        line-height: 1.25;
        font-weight: 600;
    }

    .badge-count {
        position: absolute;
        top: -5px;
        right: 8px;
        background: #2563eb;
        color: white;
        font-size: 9px;
        min-width: 18px;
        height: 18px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1.5px solid #ffffff;
        font-weight: bold;
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.35);
    }

    /* ─── BOTTOM NAV ─── */
    .bottom-nav-container {
        position: fixed;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100%;
        max-width: 480px;
        z-index: 10000;
        padding: 0 10px 8px 10px;
        background: transparent;
    }

    .bottom-nav {
        width: 100%;
        height: 58px;
        background: #ffffff;
        border-radius: 999px;
        border: 1px solid #e8f0fa;
        box-shadow: 0 8px 24px rgba(18, 59, 102, 0.12);
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
        color: #6b7280;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.2px;
        padding: 6px 0;
        transition: color 0.2s;
        position: relative;
    }

    .nav-item.active { color: #2563eb; }
    .nav-item.active span { border-bottom: 2px solid #2563eb; padding-bottom: 1px; }

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
        background: linear-gradient(145deg, #3b82f6, #2563eb);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 18px rgba(37, 99, 235, 0.35);
        font-size: 22px;
        color: #fff;
        margin-top: -18px;
        border: 3px solid #ffffff;
    }

    /* ─── TOAST ─── */
    .copy-toast {
        position: fixed;
        bottom: 100px;
        left: 50%;
        transform: translateX(-50%);
        background: #2563eb;
        color: #fff;
        padding: 10px 24px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 700;
        z-index: 99999;
        opacity: 0;
        transition: opacity 0.3s;
        pointer-events: none;
    }
    .copy-toast.show { opacity: 1; }

    .user-card [style*="color:#999"],
    .user-card [style*="color: #999"] {
        color: #6b7280 !important;
    }
    .user-card [style*="color: #333"],
    .user-card [style*="color:#333"] {
        color: #172033 !important;
    }

    @media (min-width: 900px) {
        .bottom-nav-container { max-width: 600px; }
    }
</style>

<div class="profile-page-wrapper">

    <!-- HEADER -->
    <div class="header-bg">
        <div class="header-top-row">
            <a href="{{ route('user.home') }}"><i class="fas fa-chevron-left"></i></a>
            <span class="header-title">@lang('My Account')</span>
            <div class="spacer"></div>
        </div>
    </div>

    <!-- USER CARD -->
    <div class="user-card">
        <div class="sign-in-tag">
            <i class="far fa-calendar-check"></i> @lang('Sign In') <i class="fas fa-chevron-right" style="font-size:8px;"></i>
        </div>

        <div style="display: flex; align-items: center; gap: 16px;">
            <div class="avatar-box">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->username ?? 'User') }}&background=123B66&color=F4B942&bold=true" 
                     onerror="this.src='{{ asset('assets/images/frontend/img/avatar.png') }}'">
            </div>
            <div style="flex: 1;">
                <div class="vip-tag">
                    <i class="fas fa-medal" style="font-size:8px;"></i> VIP{{ auth()->user()->vip_level ?? 1 }}
                </div>
                <div style="font-size: 20px; font-weight: 800; color: #172033; margin-top: 4px; display: flex; align-items: center; gap: 8px;">
                    {{ auth()->user()->username }}
                    <i class="far fa-copy" style="font-size:14px; color:#6b7280; cursor:pointer;" onclick="copyToClipboard('{{ auth()->user()->username }}')"></i>
                </div>
                <div style="font-size:11px; color:#6b7280; margin-top: 4px;">
                    @lang('Nickname:') {{ auth()->user()->username }} 
                    <i class="fas fa-pencil-alt" style="font-size:9px; cursor:pointer;" onclick="window.location.href='{{ route('user.profile.setting') }}'"></i>
                </div>
                <div style="font-size:10px; color:#6b7280;">
                    @lang('Joined since:') {{ showDateTime(auth()->user()->created_at, 'Y-m-d') }}
                </div>
            </div>
        </div>

        <div style="margin-top: 24px;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span style="font-size: 30px; font-weight: 900; color: #123b66;">
                    ৳ {{ number_format(auth()->user()->balance, 2) }}
                </span>
                <i class="fas fa-sync-alt" style="color:#2563eb; font-size:14px; cursor:pointer;" onclick="location.reload()"></i>
            </div>
        </div>

        <div style="display: flex; gap: 8px; margin-top: 20px;">
            <a href="{{ route('user.deposit.index') }}" class="action-btn">@lang('Deposit')</a>
            <a href="{{ route('user.withdraw') }}" class="action-btn">@lang('Withdraw')</a>
            <a href="{{ route('user.profile.setting') }}" class="action-btn">@lang('My Card')</a>
        </div>
    </div>

    <!-- MENU CONTAINER -->
    <div class="menu-container">
        <div class="section-title">@lang('Member Center')</div>
        
        <div class="menu-grid">
            <!-- Reward Center -->
            <a href="{{ route('user.redeem.index') }}" class="menu-item">
                <div class="icon-box"><i class="fas fa-trophy"></i></div>
                <span class="badge-count">3</span>
                <span class="menu-label">@lang('Reward Center')</span>
            </a>

            <!-- Betting Records -->
            <a href="{{ route('user.game.log') }}" class="menu-item">
                <div class="icon-box"><i class="fas fa-star-half-alt"></i></div>
                <span class="menu-label">@lang('Betting Records')</span>
            </a>

            <!-- Profit & Loss -->
            <a href="{{ route('user.transactions') }}" class="menu-item">
                <div class="icon-box"><i class="fas fa-file-invoice-dollar"></i></div>
                <span class="menu-label">@lang('Profit & Loss')</span>
            </a>

            <!-- Deposit Records -->
            <a href="{{ route('user.deposit.history') }}" class="menu-item">
                <div class="icon-box"><i class="fas fa-file-export"></i></div>
                <span class="menu-label">@lang('Deposit Records')</span>
            </a>

            <!-- Withdrawal Records -->
            <a href="{{ route('user.withdraw.history') }}" class="menu-item">
                <div class="icon-box"><i class="fas fa-file-import"></i></div>
                <span class="menu-label">@lang('Withdrawal Records')</span>
            </a>

            <!-- Account Records -->
            <a href="{{ route('user.transactions') }}" class="menu-item">
                <div class="icon-box"><i class="fas fa-chart-line"></i></div>
                <span class="menu-label">@lang('Account Records')</span>
            </a>

            <!-- My Account -->
            <a href="{{ route('user.profile.setting') }}" class="menu-item">
                <div class="icon-box"><i class="far fa-user"></i></div>
                <span class="menu-label">@lang('My Account')</span>
            </a>

            <!-- Security Center -->
            <a href="{{ route('user.change.password') }}" class="menu-item">
                <div class="icon-box"><i class="fas fa-shield-alt"></i></div>
                <span class="menu-label">@lang('Security Center')</span>
            </a>

            <!-- Invite Friends -->
            <a href="{{ route('user.referrals') }}" class="menu-item">
                <div class="icon-box"><i class="fas fa-user-friends"></i></div>
                <span class="menu-label">@lang('Invite Friends')</span>
            </a>

            <!-- Mission -->
            <a href="{{ route('user.promotions') }}" class="menu-item">
                <div class="icon-box"><i class="fas fa-gift"></i></div>
                <span class="badge-count">2</span>
                <span class="menu-label">@lang('Mission')</span>
            </a>

            <!-- Commission -->
            <a href="{{ route('user.commission.log') }}" class="menu-item">
                <div class="icon-box"><i class="fas fa-coins"></i></div>
                <span class="menu-label">@lang('Commission')</span>
            </a>

            <!-- 2FA Security -->
            <a href="{{ route('user.twofactor') }}" class="menu-item">
                <div class="icon-box"><i class="fas fa-key"></i></div>
                <span class="menu-label">@lang('2FA Security')</span>
            </a>

            <!-- Transaction PIN -->
            <a href="{{ route('user.withdraw.pin.change') }}" class="menu-item">
                <div class="icon-box"><i class="fas fa-lock"></i></div>
                <span class="menu-label">@lang('Transaction PIN')</span>
            </a>

            <!-- Support -->
            <a href="{{ route('ticket.open') }}" class="menu-item">
                <div class="icon-box"><i class="fas fa-headphones-alt"></i></div>
                <span class="menu-label">@lang('Customer Service')</span>
            </a>

            <!-- Logout -->
            <a href="{{ route('user.logout') }}" class="menu-item">
                <div class="icon-box"><i class="fas fa-sign-out-alt"></i></div>
                <span class="menu-label">@lang('Log Out')</span>
            </a>
        </div>
    </div>

</div>

<!-- COPY TOAST -->
<div class="copy-toast" id="copyToast">@lang('Copied!')</div>

<!-- BOTTOM NAVIGATION -->
<div class="bottom-nav-container">
    <div class="bottom-nav">
        <a href="{{ route('user.home') }}" class="nav-item">
            <i class="fas fa-home"></i>
            <span>@lang('Home')</span>
        </a>
        <a href="{{ route('user.promotions') }}" class="nav-item">
            <i class="fas fa-gift"></i>
            <span>@lang('Promotion')</span>
        </a>
        <a href="{{ route('user.referrals') }}" class="nav-item center-item">
            <div class="center-icon-circle"><i class="fas fa-share-nodes"></i></div>
            <span>@lang('Invite')</span>
        </a>
        <a href="{{ route('user.redeem.index') }}" class="nav-item">
            <i class="fas fa-trophy"></i>
            <span>@lang('Reward')</span>
        </a>
        <a href="{{ route('user.account') }}" class="nav-item active">
            <i class="fas fa-user-circle"></i>
            <span>@lang('Member')</span>
        </a>
    </div>
</div>

<script>
    function copyToClipboard(text) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text).then(function() {
                const toast = document.getElementById('copyToast');
                toast.classList.add('show');
                setTimeout(() => toast.classList.remove('show'), 2000);
            });
        } else {
            const el = document.createElement('textarea');
            el.value = text;
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
            alert('Copied: ' + text);
        }
    }
</script>

@endsection
