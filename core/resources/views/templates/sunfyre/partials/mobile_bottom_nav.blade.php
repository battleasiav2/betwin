@php
    $dockHome = request()->routeIs('home', 'user.home');
    $dockPromo = request()->routeIs('user.promotions');
    $dockInvite = request()->routeIs('user.referrals');
    $dockReward = request()->routeIs('user.redeem.*');
    $dockMember = request()->routeIs('user.account');
@endphp

<nav class="m-dock" aria-label="Mobile menu">
    <div class="m-dock__shell">
        <a href="{{ route('user.home') }}" class="m-dock__item {{ $dockHome ? 'is-active' : '' }}">
            <span class="m-dock__icon" aria-hidden="true"><i class="fas fa-home"></i></span>
            <span class="m-dock__label">@lang('Home')</span>
        </a>
        <a href="{{ route('user.promotions') }}" class="m-dock__item {{ $dockPromo ? 'is-active' : '' }}">
            <span class="m-dock__icon" aria-hidden="true"><i class="fas fa-gift"></i></span>
            <span class="m-dock__label">@lang('Promotion')</span>
        </a>
        <a href="{{ route('user.referrals') }}" class="m-dock__item m-dock__item--center {{ $dockInvite ? 'is-active' : '' }}">
            <span class="m-dock__orb" aria-hidden="true">
                <i class="fas fa-user-plus"></i>
            </span>
            <span class="m-dock__label">@lang('Invite')</span>
        </a>
        <a href="{{ route('user.redeem.index') }}" class="m-dock__item {{ $dockReward ? 'is-active' : '' }}">
            <span class="m-dock__icon" aria-hidden="true"><i class="fas fa-medal"></i></span>
            <span class="m-dock__label">@lang('Reward')</span>
        </a>
        <a href="{{ route('user.account') }}" class="m-dock__item {{ $dockMember ? 'is-active' : '' }}">
            <span class="m-dock__icon" aria-hidden="true"><i class="fas fa-user-circle"></i></span>
            <span class="m-dock__label">@lang('Member')</span>
        </a>
    </div>
</nav>
