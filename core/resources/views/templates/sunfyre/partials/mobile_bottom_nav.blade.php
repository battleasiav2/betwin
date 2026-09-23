@php
    $dockHome = request()->routeIs('home', 'user.home') && !request()->routeIs('user.promotions');
    $dockCasino = false;
    $dockPromo = request()->routeIs('user.promotions', 'user.redeem.*');
    $dockSports = false;
@endphp

<nav class="m-dock" aria-label="Mobile menu">
    <div class="m-dock__shell">
        <button type="button" class="m-dock__item" onclick="toggleSidebar()" aria-label="@lang('Menu')">
            <span class="m-dock__icon" aria-hidden="true"><i class="fas fa-bars"></i></span>
            <span class="m-dock__label">Menu</span>
        </button>
        <a href="{{ route('user.home') }}" class="m-dock__item {{ $dockHome ? 'is-active' : '' }}">
            <span class="m-dock__icon" aria-hidden="true"><i class="fas fa-home"></i></span>
            <span class="m-dock__label">হোম</span>
        </a>
        <a href="{{ route('user.home') }}#live" class="m-dock__item {{ $dockCasino ? 'is-active' : '' }}">
            <span class="m-dock__icon" aria-hidden="true"><i class="fas fa-dice"></i></span>
            <span class="m-dock__label">Casino</span>
        </a>
        <a href="{{ route('user.promotions') }}" class="m-dock__item {{ $dockPromo ? 'is-active' : '' }}">
            <span class="m-dock__icon" aria-hidden="true"><i class="fas fa-gift"></i></span>
            <span class="m-dock__label">Free money</span>
        </a>
        <a href="{{ route('user.home') }}#sports" class="m-dock__item {{ $dockSports ? 'is-active' : '' }}">
            <span class="m-dock__icon" aria-hidden="true"><i class="fas fa-futbol"></i></span>
            <span class="m-dock__label">স্পোর্টস</span>
        </a>
    </div>
</nav>
