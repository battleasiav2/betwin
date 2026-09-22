<header class="header" id="header" style="position: fixed; top: 0; left: 0; width: 100%; z-index: 9999; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4); background-color: #123b66;">
    <div class="container-fluid px-3">
        <nav class="navbar d-flex justify-content-between align-items-center py-2">

            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('user.home') }}" class="d-flex align-items-center justify-content-center" style="color: #ffc107; font-size: 28px; text-decoration: none; cursor: pointer; margin-right: 8px; transition: 0.3s;">
                    <i class="las la-angle-left"></i>
                </a>

                <a class="navbar-brand logo me-0" href="{{ route('user.home') }}" style="text-decoration: none;">
                    <img src="{{ asset('assets/images/logo_icon/logo.png') }}" alt="{{ __(gs('site_name')) }}" class="brand-logo">
                </a>
            </div>

            <div class="header-right d-flex align-items-center gap-2">
                @auth
                    <div class="dropdown">
                        <a href="javascript:void(0)" class="profile-avatar" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ asset('assets/new/0.png') }}" alt="Profile" style="width: 35px; height: 35px; border-radius: 50%; border: 2px solid #ffc107; object-fit: cover; cursor: pointer;">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end custom-dropdown-menu" aria-labelledby="profileDropdown">
                            <li><a class="dropdown-item" href="{{ route('user.profile.setting') }}"><i class="las la-user"></i> @lang('প্রোফাইল')</a></li>
                            <li><a class="dropdown-item" href="{{ route('user.deposit.history') }}"><i class="las la-history"></i> @lang('জমা রেকর্ড')</a></li>
                            <li><a class="dropdown-item" href="{{ route('user.withdraw.history') }}"><i class="las la-wallet"></i> @lang('উত্তোলন রেকর্ড')</a></li>
                            <li><a class="dropdown-item" href="{{ route('user.game.log') }}"><i class="las la-dice"></i> @lang('বেট রেকর্ড')</a></li>
                            <li><a class="dropdown-item" href="{{ route('ticket.index') }}"><i class="las la-headset"></i> @lang('সাপোর্ট টিকেট')</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="{{ route('user.logout') }}"><i class="las la-power-off"></i> @lang('লগআউট')</a></li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('user.login') }}" class="btn btn--sm btn--base">@lang('Login')</a>
                @endauth
            </div>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav nav-menu ms-auto align-items-xl-center mt-3 mt-xl-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">@lang('Home')</a>
                    </li>
                    @php
                        $pages = App\Models\Page::where('tempname', $activeTemplate)
                            ->where('is_default', Status::NO)
                            ->get();
                    @endphp
                    @foreach ($pages as $k => $data)
                        <li class="nav-item">
                            <a class="nav-link {{ menuActive('pages', [$data->slug]) }}" href="{{ route('pages', [$data->slug]) }}">{{ __($data->name) }}</a>
                        </li>
                    @endforeach
                    <li class="nav-item">
                        <a class="nav-link {{ menuActive('games') }}" href="{{ route('games') }}">@lang('Game')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ menuActive('blog') }}" href="{{ route('blog') }}">@lang('Blog')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ menuActive('contact') }}" href="{{ route('contact') }}">@lang('Contact')</a>
                    </li>
                </ul>
            </div>

        </nav>
    </div>
</header>

<style>
    .custom-dropdown-menu {
        background-color: #123b66;
        border: 1px solid #2a5a8a;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.5);
        padding: 10px 0;
        min-width: 200px;
    }
    .custom-dropdown-menu .dropdown-item {
        color: #fff;
        font-size: 14px;
        padding: 10px 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: 0.2s;
    }
    .custom-dropdown-menu .dropdown-item i {
        font-size: 18px;
        color: #ffc107;
    }
    .custom-dropdown-menu .dropdown-item:hover {
        background-color: #1a4a7a;
        color: #ffc107;
    }
    .custom-dropdown-menu .dropdown-divider {
        border-top: 1px solid #2a5a8a;
    }
</style>