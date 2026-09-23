@php
    $dockHome = request()->routeIs('home', 'user.home');
    $dockCasino = request()->routeIs('user.game.*', 'gamelunch*') || request()->get('cat') === 'casino';
    $dockPromo = request()->routeIs('user.promotions', 'user.redeem.*');
    $dockSports = request()->is('*/sports*') || request()->get('cat') === 'sports';
@endphp

<nav class="m-dock" aria-label="Mobile menu">
    <div class="m-dock__shell">
        <button type="button" class="m-dock__item" onclick="typeof toggleSidebar === 'function' && toggleSidebar()" aria-label="@lang('Menu')">
            <span class="m-dock__icon" aria-hidden="true"><i class="fas fa-bars"></i></span>
            <span class="m-dock__label">@lang('Menu')</span>
        </button>

        <a href="{{ route('user.home') }}" class="m-dock__item {{ $dockHome && !$dockCasino && !$dockSports && !$dockPromo ? 'is-active' : '' }}">
            <span class="m-dock__icon" aria-hidden="true"><i class="fas fa-home"></i></span>
            <span class="m-dock__label">@lang('Home')</span>
        </a>

        <a href="{{ route('user.promotions') }}" class="m-dock__item m-dock__item--feature {{ $dockPromo ? 'is-active' : '' }}">
            <span class="m-dock__orb" aria-hidden="true">
                <i class="fas fa-gift"></i>
            </span>
            <span class="m-dock__label">@lang('Free money')</span>
        </a>

        <a href="{{ route('user.home') }}#casino" class="m-dock__item {{ $dockCasino ? 'is-active' : '' }}">
            <span class="m-dock__icon" aria-hidden="true"><i class="fas fa-dice"></i></span>
            <span class="m-dock__label">@lang('Casino')</span>
        </a>

        <a href="{{ route('user.home') }}#sports" class="m-dock__item {{ $dockSports ? 'is-active' : '' }}">
            <span class="m-dock__icon" aria-hidden="true"><i class="fas fa-futbol"></i></span>
            <span class="m-dock__label">@lang('Sports')</span>
        </a>
    </div>
</nav>
