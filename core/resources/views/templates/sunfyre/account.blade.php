@extends($activeTemplate . 'layouts.master')
@section('content')

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        background-color: #0b0f14; 
        color: #e8eef5;
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        margin: 0; 
        padding-bottom: 80px;
        -webkit-tap-highlight-color: transparent;
    }

    .profile-page-wrapper {
        min-height: 100vh;
    }

    /* ─── HEADER ─── */
    .header-bg {
        background: linear-gradient(135deg, #0f1419 0%, #151b24 55%, #1a2330 100%);
        padding: 20px 15px 70px 15px;
        text-align: center;
        border-bottom: 1px solid rgba(232,184,74,0.2);
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
    }
    .header-top-row .spacer {
        width: 24px;
    }

    /* ─── USER CARD ─── */
    .user-card {
        background: linear-gradient(145deg, #1a2330 0%, #151b24 55%, #0f1419 100%);
        border-radius: 25px;
        margin: -50px 15px 20px 15px;
        padding: 20px;
        position: relative;
        box-shadow: 0 15px 35px rgba(0,0,0,0.55);
        color: #e8eef5;
        border: 1px solid rgba(255,255,255,0.08);
    }

    .sign-in-tag {
        position: absolute;
        top: 0;
        right: 0;
        background: linear-gradient(135deg, #e8b84a, #c49a3a);
        color: #0b0f14;
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
    }

    .avatar-box {
        width: 85px;
        height: 85px;
        border-radius: 50%;
        border: 4px solid rgba(232,184,74,0.55);
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.35);
        flex-shrink: 0;
    }
    .avatar-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .vip-tag {
        background: rgba(232,184,74,0.15);
        color: #e8b84a;
        border: 1px solid rgba(232,184,74,0.35);
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
        position: relative;
        overflow: hidden;
        background: #151b24;
        border-radius: 12px;
        padding: 10px 12px;
        font-size: 13px;
        font-weight: 800;
        color: #e8eef5;
        flex: 1;
        text-align: center;
        border: 1px solid rgba(255,255,255,0.1);
        box-shadow: none;
        text-decoration: none;
        display: inline-block;
        transition: transform 0.15s;
    }
    .action-btn:nth-child(1) {
        background: linear-gradient(135deg, rgba(45,212,168,0.14) 0%, #151b24 55%);
        border-color: rgba(45,212,168,0.28);
        color: #e8eef5;
    }
    .action-btn:nth-child(1)::before,
    .action-btn:nth-child(2)::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0; width: 3px;
        border-radius: 12px 0 0 12px;
    }
    .action-btn:nth-child(1)::before { background: #2dd4a8; }
    .action-btn:nth-child(2) {
        background: linear-gradient(135deg, rgba(232,184,74,0.14) 0%, #151b24 55%);
        border-color: rgba(232,184,74,0.28);
        color: #e8eef5;
    }
    .action-btn:nth-child(2)::before { background: #e8b84a; }
    .action-btn:active {
        transform: scale(0.98);
    }

    /* ─── MENU CONTAINER ─── */
    .menu-container {
        background-color: #0f1419;
        border-radius: 30px 30px 0 0;
        padding-top: 20px;
        margin-top: 10px;
        border-top: 1px solid rgba(255,255,255,0.06);
    }

    .section-title {
        color: #8b97a8;
        font-size: 12px;
        font-weight: bold;
        padding-left: 20px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
    }
    .section-title::after {
        content: "";
        height: 1px;
        flex: 1;
        background: rgba(255,255,255,0.08);
        margin-left: 10px;
    }

    .menu-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 25px 5px;
        padding: 20px 10px;
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
        transform: scale(0.9);
    }

    .icon-box {
        width: 52px;
        height: 52px;
        background: linear-gradient(135deg, rgba(255,255,255,0.04) 0%, #151b24 60%); 
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 8px;
        color: #e8b84a;
        font-size: 22px;
        border: 1px solid rgba(255,255,255,0.08);
    }

    .menu-label {
        font-size: 10px;
        color: #8b97a8;
        text-align: center;
        line-height: 1.2;
        font-weight: 500;
    }

    .badge-count {
        position: absolute;
        top: -5px;
        right: 8px;
        background: #ff3b30;
        color: white;
        font-size: 9px;
        min-width: 18px;
        height: 18px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1.5px solid #161616;
        font-weight: bold;
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
        background: #0b0f14;
    }

    .bottom-nav {
        width: 100%;
        height: 58px;
        background: linear-gradient(180deg, #1a2330 0%, #151b24 100%);
        border-radius: 999px;
        border: 1.5px solid #2a3544;
        box-shadow:
            0 0 0 2px #0b0f14,
            inset 0 1px 0 rgba(30,200,130,0.18),
            0 -2px 0 0 #2dd4a8,
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
        color: #8b97a8;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.2px;
        padding: 6px 0;
        transition: color 0.2s;
        position: relative;
    }

    .nav-item.active { color: #e8b84a; }
    .nav-item.active span { border-bottom: 2px solid #e8b84a; padding-bottom: 1px; }

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
        background: linear-gradient(145deg, #2dd4a8, #1fa88a);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow:
            0 0 0 3px #0b0f14,
            0 0 0 5px #2dd4a8,
            0 6px 20px rgba(0,188,140,0.55);
        font-size: 22px;
        color: #fff;
        margin-top: -18px;
        border: none;
    }

    /* ─── TOAST ─── */
    .copy-toast {
        position: fixed;
        bottom: 100px;
        left: 50%;
        transform: translateX(-50%);
        background: #43a047;
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

    @media (min-width: 900px) {
        .bottom-nav-container { max-width: 600px; }
    }
</style>

<div class="profile-page-wrapper">

    <!-- HEADER -->
    <div class="header-bg">
        <div class="header-top-row">
            <a href="{{ route('user.home') }}"><i class="fas fa-chevron-left"></i></a>
            <span class="header-title">My Account</span>
            <div class="spacer"></div>
        </div>
    </div>

    <!-- USER CARD -->
    <div class="user-card">
        <div class="sign-in-tag">
            <i class="far fa-calendar-check"></i> Sign In <i class="fas fa-chevron-right" style="font-size:8px;"></i>
        </div>

        <div style="display: flex; align-items: center; gap: 16px;">
            <div class="avatar-box">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->username ?? 'User') }}&background=ffd700&color=333&bold=true" 
                     onerror="this.src='{{ asset('assets/images/frontend/img/avatar.png') }}'">
            </div>
            <div style="flex: 1;">
                <div class="vip-tag">
                    <i class="fas fa-medal" style="font-size:8px;"></i> VIP{{ auth()->user()->vip_level ?? 1 }}
                </div>
                <div style="font-size: 20px; font-weight: 800; color: #e8eef5; margin-top: 4px; display: flex; align-items: center; gap: 8px;">
                    {{ auth()->user()->username }}
                    <i class="far fa-copy" style="font-size:14px; color:#8b97a8; cursor:pointer;" onclick="copyToClipboard('{{ auth()->user()->username }}')"></i>
                </div>
                <div style="font-size:11px; color:#8b97a8; margin-top: 4px;">
                    Nickname: {{ auth()->user()->username }} 
                    <i class="fas fa-pencil-alt" style="font-size:9px; cursor:pointer;" onclick="window.location.href='{{ route('user.profile.setting') }}'"></i>
                </div>
                <div style="font-size:10px; color:#8b97a8;">
                    Joined since: {{ showDateTime(auth()->user()->created_at, 'Y-m-d') }}
                </div>
            </div>
        </div>

        <div style="margin-top: 24px;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span style="font-size: 30px; font-weight: 900; color: #e8b84a;" class="js-live-balance" data-live-balance="sym">
                    ৳ {{ number_format(auth()->user()->balance, 2) }}
                </span>
                <i class="fas fa-sync-alt js-live-balance-refresh" style="color:#2563eb; font-size:14px; cursor:pointer;" title="@lang('Refresh')"></i>
            </div>
        </div>

        <div style="display: flex; gap: 8px; margin-top: 20px;">
            <a href="{{ route('user.deposit.index') }}" class="action-btn">Deposit</a>
            <a href="{{ route('user.withdraw') }}" class="action-btn">Withdraw</a>
            <a href="{{ route('user.profile.setting') }}" class="action-btn">My Card</a>
        </div>
    </div>

    <!-- MENU CONTAINER -->
    <div class="menu-container">
        <div class="section-title">Member Center</div>
        
        <div class="menu-grid">
            <!-- Reward Center -->
            <a href="{{ route('user.redeem.index') }}" class="menu-item">
                <div class="icon-box"><i class="fas fa-trophy"></i></div>
                <span class="badge-count">3</span>
                <span class="menu-label">Reward Center</span>
            </a>

            <!-- Betting Records -->
            <a href="{{ route('user.game.log') }}" class="menu-item">
                <div class="icon-box"><i class="fas fa-star-half-alt"></i></div>
                <span class="menu-label">Betting Records</span>
            </a>

            <!-- Profit & Loss -->
            <a href="{{ route('user.transactions') }}" class="menu-item">
                <div class="icon-box"><i class="fas fa-file-invoice-dollar"></i></div>
                <span class="menu-label">Profit & Loss</span>
            </a>

            <!-- Deposit Records -->
            <a href="{{ route('user.deposit.history') }}" class="menu-item">
                <div class="icon-box"><i class="fas fa-file-export"></i></div>
                <span class="menu-label">Deposit Records</span>
            </a>

            <!-- Withdrawal Records -->
            <a href="{{ route('user.withdraw.history') }}" class="menu-item">
                <div class="icon-box"><i class="fas fa-file-import"></i></div>
                <span class="menu-label">Withdrawal Records</span>
            </a>

            <!-- Account Records -->
            <a href="{{ route('user.transactions') }}" class="menu-item">
                <div class="icon-box"><i class="fas fa-chart-line"></i></div>
                <span class="menu-label">Account Records</span>
            </a>

            <!-- My Account -->
            <a href="{{ route('user.profile.setting') }}" class="menu-item">
                <div class="icon-box"><i class="far fa-user"></i></div>
                <span class="menu-label">My Account</span>
            </a>

            <!-- Security Center -->
            <a href="{{ route('user.change.password') }}" class="menu-item">
                <div class="icon-box"><i class="fas fa-shield-alt"></i></div>
                <span class="menu-label">Security Center</span>
            </a>

            <!-- Invite Friends -->
            <a href="{{ route('user.referrals') }}" class="menu-item">
                <div class="icon-box"><i class="fas fa-user-friends"></i></div>
                <span class="menu-label">Invite Friends</span>
            </a>

            <!-- Mission -->
            <a href="{{ route('user.promotions') }}" class="menu-item">
                <div class="icon-box"><i class="fas fa-gift"></i></div>
                <span class="badge-count">2</span>
                <span class="menu-label">Mission</span>
            </a>

            <!-- Commission -->
            <a href="{{ route('user.commission.log') }}" class="menu-item">
                <div class="icon-box"><i class="fas fa-coins"></i></div>
                <span class="menu-label">Commission</span>
            </a>

            <!-- 2FA Security -->
            <a href="{{ route('user.twofactor') }}" class="menu-item">
                <div class="icon-box"><i class="fas fa-key"></i></div>
                <span class="menu-label">2FA Security</span>
            </a>

            <!-- Transaction PIN -->
            <a href="{{ route('user.withdraw.pin.change') }}" class="menu-item">
                <div class="icon-box"><i class="fas fa-lock"></i></div>
                <span class="menu-label">Transaction PIN</span>
            </a>

            <!-- Support -->
            <a href="{{ route('ticket.open') }}" class="menu-item">
                <div class="icon-box"><i class="fas fa-headphones-alt"></i></div>
                <span class="menu-label">Customer Service</span>
            </a>

            <!-- Logout -->
            <a href="{{ route('user.logout') }}" class="menu-item">
                <div class="icon-box"><i class="fas fa-sign-out-alt"></i></div>
                <span class="menu-label">Log Out</span>
            </a>
        </div>
    </div>

</div>

<!-- COPY TOAST -->
<div class="copy-toast" id="copyToast">Copied!</div>

<!-- BOTTOM NAVIGATION -->
<div class="bottom-nav-container">
    <div class="bottom-nav">
        <a href="{{ route('user.home') }}" class="nav-item">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('user.promotions') }}" class="nav-item">
            <i class="fas fa-gift"></i>
            <span>Promotion</span>
        </a>
        <a href="{{ route('user.referrals') }}" class="nav-item center-item">
            <div class="center-icon-circle"><i class="fas fa-share-nodes"></i></div>
            <span>Invite</span>
        </a>
        <a href="{{ route('user.redeem.index') }}" class="nav-item">
            <i class="fas fa-trophy"></i>
            <span>Reward</span>
        </a>
        <a href="{{ route('user.account') }}" class="nav-item active">
            <i class="fas fa-user-circle"></i>
            <span>Member</span>
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
