<header class="header" id="header" style="position: fixed; top: 0; left: 0; width: 100%; z-index: 9999; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4); background-color: #012b27;">
    <div class="container-fluid px-3">
        <nav class="navbar d-flex justify-content-between align-items-center py-2">
            <div class="d-flex align-items-center gap-2">
                <button class="navbar-toggler p-0 border-0" type="button" id="open-sidebar" style="color: #fff; font-size: 24px; cursor: pointer;">
                    <i class="las la-bars"></i>
                </button>
                <a class="navbar-brand logo me-0" href="{{ route('home') }}" style="text-decoration: none;">
                    <img src="{{ asset('assets/images/logo_icon/logo.png') }}" alt="{{ __(gs('site_name')) }}" class="brand-logo">
                </a>
            </div>
            
            <div class="header-right d-flex align-items-center gap-2">
                @auth
                    <div class="balance-box d-flex align-items-center gap-1 px-2 py-1" style="background: #024641; border: 1px solid #065f58; border-radius: 20px; color: #fff; font-size: 13px;">
                        <span class="amount fw-bold">{{ gs('cur_sym') }}{{ number_format(auth()->user()->balance, 2) }}</span>
                        <a href="javascript:void(0)" onclick="refreshBalance(this)" class="text-white ms-1 refresh-btn" style="display: inline-flex; align-items: center; transition: transform 0.5s ease;">
                            <i class="las la-sync"></i>
                        </a>
                    </div>
                    
                    <div class="dropdown" id="userProfileDropdown">
                        <a href="javascript:void(0)" class="profile-avatar" id="profileDropdown" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                            <img src="{{ asset('assets/new/0.png') }}" alt="Profile" style="width: 35px; height: 35px; border-radius: 50%; border: 2px solid #ffc107; object-fit: cover;">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end custom-dropdown" aria-labelledby="profileDropdown">
                            <li><a class="dropdown-item profile-link" href="{{ route('user.profile.setting') }}"><i class="las la-user"></i> প্রোফাইল</a></li>
                            <li><a class="dropdown-item profile-link" href="{{ route('user.deposit.history') }}"><i class="las la-history"></i> জমা রেকর্ড</a></li>
                            <li><a class="dropdown-item profile-link" href="{{ route('user.withdraw.history') }}"><i class="las la-wallet"></i> উত্তোলন রেকর্ড</a></li>
                            <li><a class="dropdown-item profile-link" href="{{ route('user.game.log') }}"><i class="las la-dice"></i> বেট রেকর্ড</a></li>
                            <li><a class="dropdown-item profile-link" href="{{ route('ticket.index') }}"><i class="las la-headset"></i> সাপোর্ট টিকেট</a></li>
                            <li><hr class="dropdown-divider" style="border-top: 1px solid #065f58;"></li>
                            <li><a class="dropdown-item profile-link text-danger" href="{{ route('user.logout') }}" style="color: #ff4d4d !important;"><i class="las la-power-off"></i> লগআউট</a></li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('user.login') }}" class="btn btn-warning btn-sm" style="background: #ffc107; color: #000; border: none; border-radius: 20px; padding: 6px 16px; font-size: 13px; font-weight: 600; text-decoration: none;">Login</a>
                    <a href="{{ route('user.register') }}" class="btn btn-outline-light btn-sm" style="border: 1px solid #fff; color: #fff; border-radius: 20px; padding: 6px 16px; font-size: 13px; font-weight: 600; text-decoration: none;">Register</a>
                @endauth
            </div>
        </nav>
    </div>
</header>

<div id="side-nav-menu" class="side-nav">
    <div class="side-nav-header">
        <div class="side-nav-header-glow"></div>
        <button type="button" id="close-sidebar" class="side-nav-close"><i class="las la-times"></i></button>
        <a href="{{ route('home') }}" class="side-nav-logo">
            <img src="{{ asset('assets/images/logo_icon/logo.png') }}" alt="{{ __(gs('site_name')) }}" class="brand-logo brand-logo--sidebar">
        </a>
        @auth
        <div class="side-nav-profile">
            <img src="{{ asset('assets/new/0.png') }}" alt="Profile" class="side-nav-avatar">
            <div class="side-nav-userinfo">
                <span class="side-nav-username">{{ auth()->user()->username }}</span>
                <span class="side-nav-balance">{{ gs('cur_sym') }}{{ number_format(auth()->user()->balance, 2) }}</span>
            </div>
        </div>
        @else
        <div class="side-nav-guest">
            <a href="{{ route('user.login') }}" class="side-nav-login-btn">লগইন করুন</a>
        </div>
        @endauth
    </div>

    <div class="side-nav-inner">
        <div class="side-nav-grid">
            <a href="{{ route('user.home') }}?category=hot" class="nav-box"><i class="las la-fire" style="color: #ff4d4d;"></i><span>Hot Games</span></a>
            <a href="{{ route('user.referrals') }}" class="nav-box"><i class="las la-user-friends" style="color: #00d2ff;"></i><span>Invite Friends</span></a>
            <a href="{{ route('user.home') }}?category=slot" class="nav-box"><i class="las la-dice" style="color: #ffc107;"></i><span>Slots</span></a>
            <a href="{{ route('user.home') }}?category=evo" class="nav-box"><i class="las la-university" style="color: #4caf50;"></i><span>Live Casino</span></a>
            <a href="{{ route('user.home') }}?category=sports" class="nav-box"><i class="las la-trophy" style="color: #ff9800;"></i><span>Sports</span></a>
            <a href="{{ route('user.profile.setting') }}" class="nav-box"><i class="las la-gem" style="color: #e91e63;"></i><span>VIP</span></a>
            <a href="#" class="nav-box"><i class="las la-star" style="color: #f1c40f;"></i><span>Favorites</span></a>
            <a href="{{ route('user.promotions') }}" class="nav-box"><i class="las la-gift" style="color: #e74c3c;"></i><span>Promotion</span></a>
            <a href="{{ route('user.redeem.index') }}" class="nav-box"><i class="las la-medal" style="color: #9b59b6;"></i><span>Reward</span></a>
            <a href="{{ route('ticket.index') }}" class="nav-box"><i class="las la-headset" style="color: #00bcd4;"></i><span>Support</span></a>
        </div>

        @auth
        <div class="side-nav-footer">
            <div class="side-nav-divider"></div>

            <div class="footer-support-title">সাহায্য প্রয়োজন?</div>
            <div class="footer-support-icons">
                <a href="https://t.me/bet369win" target="_blank" class="footer-icon-btn" style="--icon-color:#229ED9;"><i class="lab la-telegram"></i></a>
            </div>

            <a href="{{ route('user.logout') }}" class="side-nav-logout-btn">
                <i class="las la-power-off"></i>
                <span>লগআউট</span>
            </a>

            <div class="side-nav-copyright">
                &copy; {{ date('Y') }} {{ __(gs('site_name')) }}. সর্বস্বত্ব সংরক্ষিত।
            </div>
        </div>
        @endauth
    </div>
</div>

<div id="nav-overlay" class="nav-overlay"></div>

<style>
    .side-nav {
        position: fixed;
        top: 0;
        left: -280px;
        width: 280px;
        height: 100%;
        background-color: #012b27;
        z-index: 10001;
        transition: 0.3s;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
    }
    .side-nav.open { left: 0; }

    .side-nav-header {
        position: relative;
        background: linear-gradient(135deg, #024641 0%, #012b27 100%);
        padding: 22px 18px 18px 18px;
        border-bottom: 1px solid #065f58;
        overflow: hidden;
    }
    .side-nav-close {
        position: absolute;
        top: 14px;
        right: 14px;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: rgba(0,0,0,0.25);
        border: 1px solid #065f58;
        color: #fff;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 2;
        transition: 0.2s ease;
    }
    .side-nav-close:active {
        background: #D4AF37;
        color: #012b27;
        transform: scale(0.9);
    }
    .side-nav-header-glow {
        position: absolute;
        top: -40px;
        right: -40px;
        width: 140px;
        height: 140px;
        background: radial-gradient(circle, rgba(212,175,55,0.25) 0%, transparent 70%);
        pointer-events: none;
    }
    .side-nav-logo {
        display: block;
        margin-bottom: 16px;
        position: relative;
        z-index: 1;
    }
    .side-nav-logo img {
        max-height: 34px;
    }
    .side-nav-profile {
        display: flex;
        align-items: center;
        gap: 12px;
        position: relative;
        z-index: 1;
    }
    .side-nav-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        border: 2px solid #D4AF37;
        object-fit: cover;
        box-shadow: 0 4px 10px rgba(0,0,0,0.4);
    }
    .side-nav-userinfo {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }
    .side-nav-username {
        color: #fff;
        font-weight: 700;
        font-size: 14px;
        text-transform: capitalize;
    }
    .side-nav-balance {
        color: #D4AF37;
        font-weight: 700;
        font-size: 13px;
    }
    .side-nav-guest {
        position: relative;
        z-index: 1;
    }
    .side-nav-login-btn {
        display: inline-block;
        background: #D4AF37;
        color: #012b27;
        font-weight: 700;
        font-size: 13px;
        padding: 8px 18px;
        border-radius: 20px;
        text-decoration: none;
    }

    .side-nav-inner {
        padding: 16px 12px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .side-nav-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; }
    .nav-box {
        background: #024641;
        border: 1px solid #065f58;
        border-radius: 12px;
        padding: 16px 5px;
        text-align: center;
        text-decoration: none;
        color: #fff;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        transition: 0.2s ease;
    }
    .nav-box:active {
        transform: scale(0.95);
        border-color: #D4AF37;
    }
    .nav-box i { font-size: 26px; }
    .nav-box span { font-size: 12px; font-weight: 600; }

    /* ---- Footer section ---- */
    .side-nav-footer {
        margin-top: auto;
        padding-top: 18px;
        text-align: center;
    }
    .side-nav-divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, #D4AF37 50%, transparent);
        opacity: 0.5;
        margin: 4px 0 16px 0;
    }
    .footer-support-title {
        color: #9aada9;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 0.3px;
        margin-bottom: 10px;
    }
    .footer-support-icons {
        display: flex;
        justify-content: center;
        gap: 14px;
        margin-bottom: 18px;
    }
    .footer-icon-btn {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: #024641;
        border: 1px solid #065f58;
        color: var(--icon-color, #D4AF37);
        font-size: 19px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: 0.2s ease;
    }
    .footer-icon-btn:active {
        background: var(--icon-color, #D4AF37);
        color: #012b27;
        transform: scale(0.92);
        box-shadow: 0 0 12px rgba(212,175,55,0.4);
    }
    .side-nav-logout-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        background: linear-gradient(135deg, #ff4d4d 0%, #c0392b 100%);
        color: #fff;
        font-weight: 700;
        font-size: 14px;
        padding: 12px 0;
        border-radius: 14px;
        text-decoration: none;
        box-shadow: 0 4px 14px rgba(192,57,43,0.35);
        transition: 0.2s ease;
    }
    .side-nav-logout-btn i { font-size: 18px; }
    .side-nav-logout-btn:active {
        transform: scale(0.97);
        box-shadow: 0 2px 8px rgba(192,57,43,0.4);
    }
    .side-nav-copyright {
        color: #5e7672;
        font-size: 11px;
        margin-top: 14px;
        padding-bottom: 4px;
        letter-spacing: 0.2px;
    }
    
    .nav-overlay {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.7);
        z-index: 10000;
        display: none;
    }
    .nav-overlay.show { display: block; }

    .custom-dropdown {
        background-color: #012b27 !important;
        border: 1px solid #065f58 !important;
        border-radius: 10px !important;
        box-shadow: 0 5px 15px rgba(0,0,0,0.5) !important;
        padding: 10px 0 !important;
        margin-top: 10px !important;
        right: 0 !important;
        left: auto !important;
        transform: none !important;
        min-width: 180px;
    }
    .custom-dropdown .dropdown-item {
        color: #fff !important;
        font-size: 14px !important;
        padding: 10px 20px !important;
        display: flex !important;
        align-items: center !important;
        gap: 10px !important;
    }
    .custom-dropdown .dropdown-item i { font-size: 18px; color: #ffc107; }
    .custom-dropdown .dropdown-item:hover { background-color: #024641 !important; color: #ffc107 !important; }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .spinning { animation: spin 0.8s linear; }
</style>

@push('script')
<script>
    function refreshBalance(btn) {
        $(btn).addClass('spinning');
        setTimeout(function() {
            location.reload();
        }, 800);
    }

    $(document).ready(function() {
        const sideMenu = $('#side-nav-menu');
        const overlay = $('#nav-overlay');
        const openBtn = $('#open-sidebar');
        const closeBtn = $('#close-sidebar');

        openBtn.on('click', function(e) {
            e.preventDefault();
            sideMenu.addClass('open');
            overlay.addClass('show');
            $('body').css('overflow', 'hidden');
        });

        function closeSideMenu() {
            sideMenu.removeClass('open');
            overlay.removeClass('show');
            $('body').css('overflow', 'auto');
        }

        closeBtn.on('click', closeSideMenu);
        overlay.on('click', closeSideMenu);

        $('.nav-box').on('click', function() {
            if($(this).attr('href') !== "#") {
                sideMenu.removeClass('open');
                overlay.removeClass('show');
                $('body').css('overflow', 'auto');
            }
        });

        $('.profile-link').on('click', function() {
            $('.dropdown-menu').removeClass('show');
            $('#profileDropdown').attr('aria-expanded', 'false');
        });
    });
</script>
@endpush