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
        --bg-deep:    #e8f0fa;
        --bg-main:    #f5f7fa;
        --bg-card:    #ffffff;
        --teal:       #2563eb;
        --teal-light: #2563eb;
        --gold:       #f4b942;
        --gold-dark:  #d9a12a;
        --gold-text:  #123b66;
        --text-main:  #172033;
        --text-muted: #6b7280;
        --border:     rgba(18,59,102,0.1);
    }

    .site-header {
        position: sticky; top: 0; z-index: 100; height: 56px;
        background: rgba(255,255,255,0.96); backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px); border-bottom: 1px solid var(--border);
        display: flex; align-items: center; justify-content: flex-start; padding: 0 12px;
    }
    .header-right { display: flex; align-items: center; gap: 8px; margin-left: auto; flex-shrink: 0; }

    .btn-login {
        padding: 7px 16px; border-radius: 6px; font-size: 13px; font-weight: 700;
        cursor: pointer; border: none; text-decoration: none;
        display: inline-flex; align-items: center; justify-content: center; transition: all 0.15s;
        background: linear-gradient(180deg, #3b82f6 0%, #2563eb 60%, #1d4ed8 100%);
        color: #fff; box-shadow: 0 4px 0 #1e3a8a, 0 4px 8px rgba(0,0,0,0.4);
        border-bottom: 2px solid #60a5fa;
    }
    .btn-login:active { transform: translateY(3px); box-shadow: 0 1px 0 #1e3a8a, 0 1px 4px rgba(0,0,0,0.4); }

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
        background: #e8f0fa;
        padding: 6px 12px;
        border-radius: 8px;
        border: 1px solid #d5e4f7;
        white-space: nowrap;
        color: #123b66;
        font-weight: 700;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .hamburger-btn {
        background: #e8f0fa; border: 1px solid #d5e4f7;
        border-radius: 8px; cursor: pointer; padding: 7px 8px;
        display: flex; align-items: center; justify-content: center;
        width: 36px; height: 36px; position: relative; transition: background 0.2s;
        flex-shrink: 0;
    }
    .hamburger-btn:active { background: #d5e4f7; }
    .hamburger-icon { display: flex; flex-direction: column; gap: 4px; position: relative; width: 18px; }
    .hamburger-icon .hb-arrow { display: flex; align-items: center; gap: 2px; }
    .hamburger-icon .hb-arrow-left {
        width: 0; height: 0; border-top: 4px solid transparent;
        border-bottom: 4px solid transparent; border-right: 5px solid #123b66; flex-shrink: 0;
    }
    .hamburger-icon .hb-line { height: 2px; background: #123b66; border-radius: 2px; display: block; }
    .hamburger-icon .hb-line.l1 { width: 100%; }
    .hamburger-icon .hb-line.l2 { width: 100%; }
    .hamburger-icon .hb-line.l3 { width: 65%; }

    /* ─── SIDEBAR (white / light navy — matches site) ─── */
    @media (min-width: 900px) {
        #sidebarOverlay { display: none !important; }
        #sidebar {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            transform: translateX(0) !important;
            height: 100vh !important;
            z-index: 50 !important;
            box-shadow: 2px 0 16px rgba(18,59,102,0.1) !important;
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
        background-color: #ffffff !important;
        width: 280px !important;
        height: 100% !important;
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        z-index: 70 !important;
        overflow-y: auto !important;
        overflow-x: hidden !important;
        box-shadow: 2px 0 16px rgba(18,59,102,0.1) !important;
    }
    #sidebar::-webkit-scrollbar { display: none !important; }
    #sidebar { -ms-overflow-style: none !important; scrollbar-width: none !important; }

    .sb-header {
        background-color: #ffffff !important;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        padding: 14px 16px !important;
        border-bottom: 1px solid #e8f0fa !important;
    }
    .sb-close-btn {
        background: #e8f0fa !important;
        border: none !important;
        color: #123b66 !important;
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
    .sb-close-btn:hover { background: #d5e4f7 !important; }

    @media (min-width: 900px) {
        .sb-close-btn { display: none !important; }
    }

    .sb-menu-grid {
        display: grid !important;
        grid-template-columns: 1fr 1fr !important;
        gap: 12px !important;
        padding: 14px !important;
        background: #f5f7fa !important;
    }

    .sb-menu-card {
        background: linear-gradient(160deg, #ffffff 0%, #f3f7fc 100%) !important;
        border-radius: 18px !important;
        padding: 16px 10px 14px !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 10px !important;
        text-decoration: none !important;
        cursor: pointer !important;
        min-height: 108px !important;
        position: relative !important;
        overflow: hidden !important;
        border: 1px solid #d5e4f7 !important;
        box-shadow:
            0 10px 22px rgba(18, 59, 102, 0.08),
            inset 0 1px 0 rgba(255, 255, 255, 0.9) !important;
        transition: transform 0.15s ease, box-shadow 0.15s ease !important;
    }

    .sb-menu-card::before {
        content: "" !important;
        position: absolute !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        height: 4px !important;
        background: linear-gradient(90deg, #2563eb, #123b66) !important;
    }

    .sb-menu-card:nth-child(4n+2)::before,
    .sb-menu-card:nth-child(4n+3)::before {
        background: linear-gradient(90deg, #f4b942, #d9a12a) !important;
    }

    .sb-menu-card:active {
        transform: translateY(2px) scale(0.98) !important;
        box-shadow: 0 4px 12px rgba(18, 59, 102, 0.1) !important;
    }

    .sb-card-icon {
        width: 52px !important;
        height: 52px !important;
        border-radius: 16px !important;
        font-size: 22px !important;
        line-height: 1 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        background: #ffffff !important;
        border: 1px solid #d5e4f7 !important;
        box-shadow: 0 8px 16px rgba(18, 59, 102, 0.1) !important;
    }

    .sb-card-label {
        color: #123b66 !important;
        font-size: 12.5px !important;
        font-weight: 700 !important;
        text-align: center !important;
        line-height: 1.2 !important;
        letter-spacing: 0.2px !important;
        font-family: Arial, sans-serif !important;
    }

    .ic-red  { color: #123b66 !important; background: #ffffff !important; }
    .ic-teal { color: #2563eb !important; background: #ffffff !important; }
    .ic-gold { color: #d9a12a !important; background: #ffffff !important; }

    .sb-bottom-strip {
        padding: 0 16px 40px 16px !important;
        background: #f5f7fa !important;
    }
    .btn-logout-sidebar {
        background: #ffffff !important;
        border: 1px solid rgba(239,68,68,0.25) !important;
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
        box-shadow: 0 4px 12px rgba(18,59,102,0.08) !important;
    }
    .btn-logout-sidebar:active {
        transform: translateY(2px) !important;
    }

    #sidebarOverlay {
        background-color: rgba(18,59,102,0.35) !important;
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
