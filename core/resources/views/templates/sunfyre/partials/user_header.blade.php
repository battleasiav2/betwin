{{-- user_header.blade.php --}}

<div class="site-topbar">
@include($activeTemplate . 'partials.apk_banner')

<header class="site-header">
    <button class="hamburger-btn" onclick="toggleSidebar()">
        <div class="hamburger-icon">
            <div class="hb-arrow">
                <span class="hb-arrow-left"></span>
                <span class="hb-line l1"></span>
            </div>
            <span class="hb-line l2"></span>
            <span class="hb-line l3"></span>
        </div>
    </button>
    <a class="navbar-brand logo me-auto" href="{{ route('home') }}" style="text-decoration: none; margin-left: 8px; display:flex; align-items:center;">
        <img src="{{ asset('assets/images/logo_icon/logo.png') }}" alt="{{ __(gs('site_name')) }}" class="site-logo-img">
    </a>
    <div class="header-right">
        <a href="{{ route('download.apk') }}" class="btn-app-install" id="headerAppInstall" title="@lang('Install App')" onclick="if(window.__b369InstallApp){event.preventDefault();window.__b369InstallApp();}">
            <i class="fas fa-cloud-arrow-down"></i>
            <span>APP</span>
        </a>
        @auth
            <div class="user-balance">
                <i class="fas fa-wallet"></i>
                <span class="js-live-balance" data-live-balance="full">{{ showAmount(auth()->user()->balance) }} {{ __(gs('cur_text')) }}</span>
            </div>
        @else
            <a href="{{ route('user.login') }}" class="btn-login">Log In</a>
            <a href="{{ route('user.register') }}" class="btn-register">Register</a>
        @endauth
    </div>
</header>
</div>

<div id="sidebarOverlay" onclick="toggleSidebar()" class="hidden"></div>

<div id="sidebar">
    <div class="sb-header">
        <a href="{{ route('home') }}" style="text-decoration: none;">
            <img src="{{ asset('assets/images/logo_icon/logo.png') }}" alt="{{ __(gs('site_name')) }}" class="sidebar-logo-img">
        </a>
        <button onclick="toggleSidebar()" class="sb-close-btn">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="sb-menu-grid">
        <a href="{{ route('user.home') }}" class="sb-menu-card">
            <div class="sb-card-icon ic-red"><i class="fa-solid fa-fire"></i></div>
            <span class="sb-card-label">Hot Games</span>
        </a>

        <a href="{{ route('user.referrals') }}" class="sb-menu-card">
            <div class="sb-card-icon ic-teal"><i class="fa-solid fa-user-group"></i></div>
            <span class="sb-card-label">Invite friends</span>
        </a>

        <a href="javascript:void(0)" class="sb-menu-card" onclick="if(window.B369Fav){B369Fav.showFavorites();} if(typeof toggleSidebar==='function') toggleSidebar();">
            <div class="sb-card-icon ic-red"><i class="fa-solid fa-heart"></i></div>
            <span class="sb-card-label">Favorites</span>
        </a>

        <a href="{{ route('user.promotions') }}" class="sb-menu-card">
            <div class="sb-card-icon ic-gold"><i class="fa-solid fa-gift"></i></div>
            <span class="sb-card-label">Promotion</span>
        </a>

        <a href="#" class="sb-menu-card">
            <div class="sb-card-icon ic-red"><i class="fa-solid fa-dice"></i></div>
            <span class="sb-card-label">Slots</span>
        </a>

        <a href="{{ route('user.redeem.index') }}" class="sb-menu-card">
            <div class="sb-card-icon ic-gold"><i class="fa-solid fa-award"></i></div>
            <span class="sb-card-label">Reward Center</span>
        </a>

        <a href="#" class="sb-menu-card">
            <div class="sb-card-icon ic-red"><i class="fa-solid fa-dharmachakra"></i></div>
            <span class="sb-card-label">Live Casino</span>
        </a>

        <a href="#" class="sb-menu-card">
            <div class="sb-card-icon ic-gold"><i class="fa-solid fa-hand-holding-dollar"></i></div>
            <span class="sb-card-label">Manual Rebate</span>
        </a>

        <a href="javascript:void(0)" class="sb-menu-card" onclick="if(typeof filterGames==='function'){filterGames('sports', document.querySelector('.cat-pill[onclick*=\'sports\']'));} if(typeof toggleSidebar==='function') toggleSidebar();">
            <div class="sb-card-icon ic-red"><i class="fa-solid fa-cricket-bat-ball"></i></div>
            <span class="sb-card-label">Sports</span>
        </a>

        <a href="#" class="sb-menu-card">
            <div class="sb-card-icon ic-gold"><i class="fa-solid fa-gem"></i></div>
            <span class="sb-card-label">VIP</span>
        </a>

        <a href="#" class="sb-menu-card">
            <div class="sb-card-icon ic-red"><i class="fa-solid fa-gamepad"></i></div>
            <span class="sb-card-label">E-sports</span>
        </a>

        <a href="#" class="sb-menu-card">
            <div class="sb-card-icon ic-gold"><i class="fa-solid fa-bullseye"></i></div>
            <span class="sb-card-label">Mission</span>
        </a>

        <a href="#" class="sb-menu-card">
            <div class="sb-card-icon ic-red"><i class="fa-solid fa-spade"></i></div>
            <span class="sb-card-label">Poker</span>
        </a>

        <a href="#" class="sb-menu-card">
            <div class="sb-card-icon ic-red"><i class="fa-solid fa-fish"></i></div>
            <span class="sb-card-label">Fish</span>
        </a>

        <a href="#" class="sb-menu-card">
            <div class="sb-card-icon ic-red"><i class="fa-solid fa-ticket"></i></div>
            <span class="sb-card-label">Lottery</span>
        </a>

        <a href="{{ route('download.apk') }}" class="sb-menu-card" id="sbAppInstall" onclick="if(window.__b369InstallApp){event.preventDefault();window.__b369InstallApp();}">
            <div class="sb-card-icon ic-teal"><i class="fa-solid fa-cloud-arrow-down"></i></div>
            <span class="sb-card-label">APP Download</span>
        </a>

        <a href="#" class="sb-menu-card">
            <div class="sb-card-icon ic-teal"><i class="fa-solid fa-headset"></i></div>
            <span class="sb-card-label">Customer Service</span>
        </a>

        <a href="#" class="sb-menu-card">
            <div class="sb-card-icon ic-gold"><i class="fa-solid fa-handshake"></i></div>
            <span class="sb-card-label">Affiliate</span>
        </a>
    </div>

    <div class="sb-bottom-strip">
        <a href="{{ route('user.logout') }}" class="btn-logout-sidebar">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</div>

<style>
    :root {
        --bg-deep:    #0b0f14;
        --bg-main:    #0f1419;
        --bg-card:    #151b24;
        --teal:       #1fa88a;
        --teal-light: #2dd4a8;
        --gold:       #e8b84a;
        --gold-dark:  #c49a3a;
        --gold-text:  #e8b84a;
        --text-main:  #e8eef5;
        --text-muted: #8b97a8;
        --border:     rgba(255,255,255,0.08);
    }

    .site-topbar {
        position: sticky; top: 0; z-index: 100;
        background: #0b0f14;
    }

    .apk-banner {
        display: flex; align-items: center; gap: 10px;
        padding: 8px 10px; min-height: 52px; box-sizing: border-box;
        background: linear-gradient(90deg, #0d1f1a 0%, #123028 55%, #0f1419 100%);
        color: #fff; border-bottom: 1px solid rgba(45,212,168,0.18);
    }
    .apk-banner.is-hidden { display: none !important; }
    .apk-banner__close {
        width: 26px; height: 26px; border-radius: 50%; border: 0; flex-shrink: 0;
        background: rgba(255,255,255,0.1); color: #c8d4e0; cursor: pointer;
        display: inline-flex; align-items: center; justify-content: center;
    }
    .apk-banner__icon {
        width: 40px; height: 40px; border-radius: 10px; object-fit: cover; flex-shrink: 0;
        background: #151b24;
    }
    .apk-banner__meta { flex: 1; min-width: 0; display: flex; flex-direction: column; gap: 2px; }
    .apk-banner__title {
        font-size: 13px; font-weight: 800; color: #e8eef5;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .apk-banner__stars { color: #e8b84a; font-size: 10px; display: inline-flex; gap: 2px; }
    .apk-banner__btn {
        flex-shrink: 0; padding: 8px 12px; border-radius: 8px; font-size: 11px; font-weight: 800;
        text-decoration: none; color: #0b0f14; background: #2dd4a8; border: 0; cursor: pointer;
        letter-spacing: 0.3px;
    }
    .apk-banner__btn:active { transform: scale(0.97); }

    .site-header {
        position: relative; top: auto; z-index: 1; height: 58px;
        background: rgba(11,15,20,0.92); backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px); border-bottom: 1px solid var(--border);
        display: flex; align-items: center; justify-content: flex-start; gap: 8px; padding: 0 10px;
    }
    .navbar-brand.logo {
        flex: 1 1 auto;
        min-width: 0;
        margin-left: 4px !important;
        margin-right: 6px;
        overflow: hidden;
    }
    .site-logo-img {
        height: 36px;
        width: auto;
        max-width: 100%;
        object-fit: contain;
        display: block;
    }
    .sidebar-logo-img {
        height: 40px;
        width: auto;
        max-width: 180px;
        object-fit: contain;
        display: block;
    }
    .header-right { display: flex; align-items: center; gap: 6px; margin-left: auto; flex-shrink: 0; }

    .btn-app-install {
        padding: 7px 10px; border-radius: 10px; font-size: 11px; font-weight: 800;
        cursor: pointer; text-decoration: none; letter-spacing: 0.3px;
        display: inline-flex; align-items: center; justify-content: center; gap: 5px;
        background: linear-gradient(135deg, rgba(45,212,168,0.22) 0%, #151b24 55%);
        color: #2dd4a8; border: 1px solid rgba(45,212,168,0.4);
        transition: all 0.15s; white-space: nowrap; flex-shrink: 0;
    }
    .btn-app-install i { font-size: 13px; }
    .btn-app-install:active { transform: scale(0.96); }
    @media (max-width: 380px) {
        .btn-app-install span { display: none; }
        .btn-app-install { padding: 8px 9px; }
    }

    .btn-login {
        padding: 8px 14px; border-radius: 10px; font-size: 12px; font-weight: 800;
        cursor: pointer; text-decoration: none;
        display: inline-flex; align-items: center; justify-content: center; transition: all 0.15s;
        position: relative; overflow: hidden;
        background: linear-gradient(135deg, rgba(45,212,168,0.14) 0%, #151b24 55%);
        color: #e8eef5; border: 1px solid rgba(45,212,168,0.28); box-shadow: none;
    }
    .btn-login::before {
        content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 3px;
        background: #2dd4a8; border-radius: 10px 0 0 10px;
    }
    .btn-login:active { transform: scale(0.98); }

    .btn-register {
        padding: 8px 14px; border-radius: 10px; font-size: 12px; font-weight: 800;
        cursor: pointer; text-decoration: none;
        display: inline-flex; align-items: center; justify-content: center; transition: all 0.15s;
        position: relative; overflow: hidden;
        background: linear-gradient(135deg, rgba(232,184,74,0.14) 0%, #151b24 55%);
        color: #e8eef5; border: 1px solid rgba(232,184,74,0.28); box-shadow: none;
    }
    .btn-register::before {
        content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 3px;
        background: #e8b84a; border-radius: 10px 0 0 10px;
    }
    .btn-register:active { transform: scale(0.98); }

    .user-balance {
        background: rgba(232, 184, 74, 0.1);
        padding: 6px 12px;
        border-radius: 8px;
        border: 1px solid rgba(232, 184, 74, 0.25);
        white-space: nowrap;
        color: #e8b84a;
        font-weight: 700;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .hamburger-btn {
        background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.12);
        border-radius: 8px; cursor: pointer; padding: 7px 8px;
        display: flex; align-items: center; justify-content: center;
        width: 36px; height: 36px; position: relative; transition: background 0.2s;
        flex-shrink: 0;
    }
    .hamburger-btn:active { background: rgba(255,255,255,0.15); }
    .hamburger-icon { display: flex; flex-direction: column; gap: 4px; position: relative; width: 18px; }
    .hamburger-icon .hb-arrow { display: flex; align-items: center; gap: 2px; }
    .hamburger-icon .hb-arrow-left {
        width: 0; height: 0; border-top: 4px solid transparent;
        border-bottom: 4px solid transparent; border-right: 5px solid #fff; flex-shrink: 0;
    }
    .hamburger-icon .hb-line { height: 2px; background: #fff; border-radius: 2px; display: block; }
    .hamburger-icon .hb-line.l1 { width: 100%; }
    .hamburger-icon .hb-line.l2 { width: 100%; }
    .hamburger-icon .hb-line.l3 { width: 65%; }

    /* ─── SIDEBAR ─── */
    @media (min-width: 900px) {
        #sidebarOverlay { display: none !important; }
        #sidebar {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            transform: translateX(0) !important;
            height: 100vh !important;
            z-index: 50 !important;
            box-shadow: 2px 0 16px rgba(0,0,0,0.4) !important;
        }
        body { padding-left: 280px; }
    }

    @media (max-width: 899px) {
        #sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }
        #sidebar.open {
            transform: translateX(0) !important;
        }
    }

    #sidebar {
        background-color: #0f1419 !important;
        width: 280px !important;
        height: 100% !important;
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        z-index: 70 !important;
        overflow-y: auto !important;
        overflow-x: hidden !important;
    }
    #sidebar::-webkit-scrollbar { display: none !important; }
    #sidebar { -ms-overflow-style: none !important; scrollbar-width: none !important; }

    .sb-header {
        background-color: #0f1419 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        padding: 14px 16px !important;
        border-bottom: 1px solid rgba(255,255,255,0.06) !important;
    }
    .sb-close-btn {
        background: transparent !important;
        border: none !important;
        color: #ffffff !important;
        width: 34px !important;
        height: 34px !important;
        border-radius: 50% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        cursor: pointer !important;
        font-size: 18px !important;
        transition: background 0.2s !important;
    }
    .sb-close-btn:hover { background: rgba(255,255,255,0.1) !important; }

    @media (min-width: 900px) {
        .sb-close-btn { display: none !important; }
    }

    .sb-menu-grid {
        display: flex !important;
        flex-direction: column !important;
        gap: 8px !important;
        padding: 12px 14px !important;
    }

    .sb-menu-card {
        background: linear-gradient(135deg, rgba(255,255,255,0.04) 0%, #151b24 60%) !important;
        border-radius: 12px !important;
        padding: 11px 14px !important;
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        justify-content: flex-start !important;
        gap: 12px !important;
        text-decoration: none !important;
        cursor: pointer !important;
        min-height: 0 !important;
        position: relative !important;
        overflow: hidden !important;
        border: 1px solid rgba(255,255,255,0.08) !important;
        box-shadow: none !important;
        transition: transform 0.12s ease, border-color 0.15s ease, background 0.15s ease !important;
    }

    .sb-menu-card::before {
        content: "" !important;
        position: absolute !important;
        left: 0 !important;
        top: 0 !important;
        bottom: 0 !important;
        width: 3px !important;
        background: #2dd4a8 !important;
        border-radius: 12px 0 0 12px !important;
        opacity: 0.85 !important;
    }

    .sb-menu-card:nth-child(even)::before {
        background: #e8b84a !important;
    }

    .sb-menu-card:active {
        transform: scale(0.98) !important;
        border-color: rgba(45,212,168,0.35) !important;
        box-shadow: none !important;
    }

    .sb-card-icon {
        width: 36px !important;
        height: 36px !important;
        border-radius: 10px !important;
        font-size: 16px !important;
        line-height: 1 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        flex-shrink: 0 !important;
        background: rgba(255,255,255,0.04) !important;
        box-shadow: inset 0 0 0 1px rgba(255,255,255,0.06) !important;
    }

    .sb-card-label {
        color: #e8eef5 !important;
        font-size: 13px !important;
        font-weight: 700 !important;
        text-align: left !important;
        line-height: 1.2 !important;
        letter-spacing: 0.2px !important;
        font-family: Arial, sans-serif !important;
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
    }

    .ic-red  { color: #f07167 !important; }
    .ic-teal { color: #2dd4a8 !important; }
    .ic-gold { color: #e8b84a !important; }

    .sb-bottom-strip {
        padding: 0 16px 40px 16px !important;
    }
    .btn-logout-sidebar {
        background: rgba(239,68,68,0.08) !important;
        border: 1px solid rgba(239,68,68,0.25) !important;
        border-bottom: 3px solid rgba(180,30,30,0.5) !important;
        color: #ef4444 !important;
        padding: 12px !important;
        border-radius: 10px !important;
        text-align: center !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 8px !important;
        text-decoration: none !important;
        font-size: 14px !important;
        font-weight: 700 !important;
        width: 100% !important;
        transition: all 0.15s !important;
        box-shadow: 0 4px 0 rgba(120,20,20,0.4) !important;
    }
    .btn-logout-sidebar:active {
        transform: translateY(3px) !important;
        box-shadow: 0 1px 0 rgba(120,20,20,0.4) !important;
    }

    #sidebarOverlay {
        background-color: rgba(0,0,0,0.65) !important;
        backdrop-filter: blur(6px) !important;
        -webkit-backdrop-filter: blur(6px) !important;
        z-index: 60 !important;
        position: fixed !important;
        top: 0; left: 0; right: 0; bottom: 0;
    }
    .hidden { display: none !important; }
</style>

@push('script')
<script>
    function toggleSidebar() {
        if (window.innerWidth >= 900) return;
        var sidebar = document.getElementById('sidebar');
        var overlay = document.getElementById('sidebarOverlay');
        if(sidebar) sidebar.classList.toggle('open');
        if(overlay) overlay.classList.toggle('hidden');
    }
</script>
@endpush
