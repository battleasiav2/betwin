{{-- user_header.blade.php --}}

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
    <a class="navbar-brand logo me-auto" href="{{ route('home') }}" style="text-decoration: none; margin-left: 8px;">
        <img src="{{ asset('assets/images/logo_icon/logo.png') }}" alt="{{ __(gs('site_name')) }}" class="brand-logo">
    </a>
    <div class="header-right">
        @include($activeTemplate . 'partials.lang_switch')
        @auth
            <div class="user-balance">
                <i class="fas fa-wallet"></i>
                <span>{{ showAmount(auth()->user()->balance) }} {{ __(gs('cur_text')) }}</span>
            </div>
        @else
            <a href="{{ route('user.login') }}" class="btn-login">@lang('Log In')</a>
        @endauth
    </div>
</header>

<div id="sidebarOverlay" onclick="toggleSidebar()" class="hidden"></div>

<div id="sidebar">
    <div class="sb-header">
        <a href="{{ route('home') }}" style="text-decoration: none;">
            <img src="{{ asset('assets/images/logo_icon/logo.png') }}" alt="{{ __(gs('site_name')) }}" class="brand-logo brand-logo--sidebar">
        </a>
        <button onclick="toggleSidebar()" class="sb-close-btn">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="sb-menu-grid">
        <a href="{{ route('user.home') }}" class="sb-menu-card">
            <div class="sb-card-icon ic-red"><i class="fa-solid fa-fire"></i></div>
            <span class="sb-card-label">@lang('Hot Games')</span>
        </a>

        <a href="{{ route('user.referrals') }}" class="sb-menu-card">
            <div class="sb-card-icon ic-teal"><i class="fa-solid fa-user-group"></i></div>
            <span class="sb-card-label">@lang('Invite friends')</span>
        </a>

        <a href="#" class="sb-menu-card">
            <div class="sb-card-icon ic-red"><i class="fa-solid fa-heart"></i></div>
            <span class="sb-card-label">@lang('Favorites')</span>
        </a>

        <a href="{{ route('user.promotions') }}" class="sb-menu-card">
            <div class="sb-card-icon ic-gold"><i class="fa-solid fa-gift"></i></div>
            <span class="sb-card-label">@lang('Promotion')</span>
        </a>

        <a href="#" class="sb-menu-card">
            <div class="sb-card-icon ic-red"><i class="fa-solid fa-dice"></i></div>
            <span class="sb-card-label">@lang('Slots')</span>
        </a>

        <a href="{{ route('user.redeem.index') }}" class="sb-menu-card">
            <div class="sb-card-icon ic-gold"><i class="fa-solid fa-award"></i></div>
            <span class="sb-card-label">@lang('Reward Center')</span>
        </a>

        <a href="#" class="sb-menu-card">
            <div class="sb-card-icon ic-red"><i class="fa-solid fa-dharmachakra"></i></div>
            <span class="sb-card-label">@lang('Live Casino')</span>
        </a>

        <a href="#" class="sb-menu-card">
            <div class="sb-card-icon ic-gold"><i class="fa-solid fa-hand-holding-dollar"></i></div>
            <span class="sb-card-label">@lang('Manual Rebate')</span>
        </a>

        <a href="#" class="sb-menu-card">
            <div class="sb-card-icon ic-red"><i class="fa-solid fa-futbol"></i></div>
            <span class="sb-card-label">@lang('Sports')</span>
        </a>

        <a href="#" class="sb-menu-card">
            <div class="sb-card-icon ic-gold"><i class="fa-solid fa-gem"></i></div>
            <span class="sb-card-label">@lang('VIP')</span>
        </a>

        <a href="#" class="sb-menu-card">
            <div class="sb-card-icon ic-red"><i class="fa-solid fa-gamepad"></i></div>
            <span class="sb-card-label">@lang('E-sports')</span>
        </a>

        <a href="#" class="sb-menu-card">
            <div class="sb-card-icon ic-gold"><i class="fa-solid fa-bullseye"></i></div>
            <span class="sb-card-label">@lang('Mission')</span>
        </a>

        <a href="#" class="sb-menu-card">
            <div class="sb-card-icon ic-red"><i class="fa-solid fa-chess"></i></div>
            <span class="sb-card-label">@lang('Poker')</span>
        </a>

        <a href="#" class="sb-menu-card">
            <div class="sb-card-icon ic-red"><i class="fa-solid fa-fish"></i></div>
            <span class="sb-card-label">@lang('Fish')</span>
        </a>

        <a href="#" class="sb-menu-card">
            <div class="sb-card-icon ic-red"><i class="fa-solid fa-ticket"></i></div>
            <span class="sb-card-label">@lang('Lottery')</span>
        </a>

        <a href="#" class="sb-menu-card">
            <div class="sb-card-icon ic-teal"><i class="fa-solid fa-cloud-arrow-down"></i></div>
            <span class="sb-card-label">@lang('APP Download')</span>
        </a>

        <a href="#" class="sb-menu-card">
            <div class="sb-card-icon ic-teal"><i class="fa-solid fa-headset"></i></div>
            <span class="sb-card-label">@lang('Customer Service')</span>
        </a>

        <a href="#" class="sb-menu-card">
            <div class="sb-card-icon ic-gold"><i class="fa-solid fa-handshake"></i></div>
            <span class="sb-card-label">@lang('Affiliate')</span>
        </a>
    </div>

    <div class="sb-bottom-strip">
        <a href="{{ route('user.logout') }}" class="btn-logout-sidebar">
            <i class="fas fa-sign-out-alt"></i> @lang('Logout')
        </a>
    </div>
</div>

<style>
    :root {
        --bg-deep:    #071f18;
        --bg-main:    #0a2e22;
        --bg-card:    #0d3d2c;
        --teal:       #0d7a55;
        --teal-light: #13a36e;
        --gold:       #f0c030;
        --gold-dark:  #c89a10;
        --gold-text:  #ffd84d;
        --text-main:  #e8f5ee;
        --text-muted: #7eb89a;
        --border:     rgba(255,255,255,0.08);
    }

    .site-header {
        position: sticky; top: 0; z-index: 100; height: 56px;
        background: rgba(7,31,24,0.92); backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px); border-bottom: 1px solid var(--border);
        display: flex; align-items: center; justify-content: flex-start; padding: 0 12px;
    }
    .header-right { display: flex; align-items: center; gap: 8px; margin-left: auto; flex-shrink: 0; }

    .btn-login {
        padding: 7px 16px; border-radius: 6px; font-size: 13px; font-weight: 700;
        cursor: pointer; border: none; text-decoration: none;
        display: inline-flex; align-items: center; justify-content: center; transition: all 0.15s;
        background: linear-gradient(180deg, #1a9966 0%, #0d7a55 60%, #0a5c3e 100%);
        color: #fff; box-shadow: 0 4px 0 #064028, 0 4px 8px rgba(0,0,0,0.4);
        border-bottom: 2px solid #1dcc85;
    }
    .btn-login:active { transform: translateY(3px); box-shadow: 0 1px 0 #064028, 0 1px 4px rgba(0,0,0,0.4); }

    .btn-register {
        padding: 7px 16px; border-radius: 6px; font-size: 13px; font-weight: 700;
        cursor: pointer; border: none; text-decoration: none;
        display: inline-flex; align-items: center; justify-content: center; transition: all 0.15s;
        background: linear-gradient(180deg, #ffe066 0%, #f0c030 60%, #c89a10 100%);
        color: #3a2500; box-shadow: 0 4px 0 #8a6a00, 0 4px 8px rgba(0,0,0,0.4);
        border-bottom: 2px solid #ffe57a;
    }
    .btn-register:active { transform: translateY(3px); box-shadow: 0 1px 0 #8a6a00, 0 1px 4px rgba(0,0,0,0.4); }

    .user-balance {
        background: rgba(240, 192, 48, 0.1);
        padding: 6px 12px;
        border-radius: 8px;
        border: 1px solid rgba(240, 192, 48, 0.25);
        white-space: nowrap;
        color: #f0c030;
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
        background-color: #062c23 !important;
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
        background-color: #062c23 !important;
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
        display: grid !important;
        grid-template-columns: 1fr 1fr !important;
        gap: 10px !important;
        padding: 14px !important;
    }

    .sb-menu-card {
        background: #093327 !important;
        border-radius: 14px !important;
        padding: 18px 8px 14px !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 10px !important;
        text-decoration: none !important;
        cursor: pointer !important;
        min-height: 100px !important;
        position: relative !important;

        border-top: 1.5px solid rgba(30, 180, 130, 0.35) !important;
        border-left: 1.5px solid rgba(30, 180, 130, 0.25) !important;
        border-right: 1.5px solid rgba(10, 60, 40, 0.8) !important;
        border-bottom: 3px solid #031913 !important;

        box-shadow:
            inset 0 1px 0 rgba(255,255,255,0.07),
            0 6px 0 #031510,
            0 8px 16px rgba(0,0,0,0.45) !important;

        transition: transform 0.12s ease, box-shadow 0.12s ease !important;
    }

    .sb-menu-card:active {
        transform: translateY(5px) !important;
        border-bottom-width: 1px !important;
        box-shadow:
            inset 0 1px 0 rgba(255,255,255,0.07),
            0 1px 0 #031510,
            0 2px 6px rgba(0,0,0,0.3) !important;
    }

    .sb-card-icon {
        font-size: 34px !important;
        line-height: 1 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    .sb-card-label {
        color: #ffffff !important;
        font-size: 13.5px !important;
        font-weight: 700 !important;
        text-align: center !important;
        line-height: 1.2 !important;
        letter-spacing: 0.2px !important;
        font-family: Arial, sans-serif !important;
    }

    .ic-red  { color: #d76d5e !important; }
    .ic-teal { color: #49b9ab !important; }
    .ic-gold { color: #db9c3f !important; }

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
