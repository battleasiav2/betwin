{{-- Unique BET369WIN offer banners — real promo look, site navy/gold --}}
@php
    $depositUrl = auth()->check() ? route('user.deposit.index') : route('user.login');
    $promoUrl = route('user.promotions');
    $rewardUrl = auth()->check() ? route('user.redeem.index') : route('user.login');
@endphp

<div class="slider-wrap">
    <div class="swiper mainSlider offer-slider">
        <div class="swiper-wrapper">

            {{-- 1. Deposit Offer --}}
            <div class="swiper-slide">
                <a href="{{ $depositUrl }}" class="ob ob--deposit">
                    <div class="ob__art" aria-hidden="true">
                        <span class="ob__ring"></span>
                        <span class="ob__coin ob__coin--1">৳</span>
                        <span class="ob__coin ob__coin--2">+</span>
                        <span class="ob__blob"></span>
                    </div>
                    <div class="ob__body">
                        <span class="ob__tag">@lang('DEPOSIT OFFER')</span>
                        <div class="ob__row">
                            <span class="ob__pct">100%</span>
                            <div class="ob__texts">
                                <strong class="ob__title">@lang('First Deposit Bonus')</strong>
                                <span class="ob__sub">@lang('Double your first top-up instantly')</span>
                            </div>
                        </div>
                        <span class="ob__btn">@lang('Deposit Now') <i class="fas fa-arrow-right"></i></span>
                    </div>
                </a>
            </div>

            {{-- 2. Big Offer --}}
            <div class="swiper-slide">
                <a href="{{ $promoUrl }}" class="ob ob--big">
                    <div class="ob__art" aria-hidden="true">
                        <span class="ob__shine"></span>
                        <span class="ob__gift"><i class="fas fa-gift"></i></span>
                        <span class="ob__spark ob__spark--a"></span>
                        <span class="ob__spark ob__spark--b"></span>
                    </div>
                    <div class="ob__body">
                        <span class="ob__tag ob__tag--gold">@lang('BIG OFFER')</span>
                        <div class="ob__row">
                            <span class="ob__pct ob__pct--gold">৳5K</span>
                            <div class="ob__texts">
                                <strong class="ob__title">@lang('Welcome Mega Pack')</strong>
                                <span class="ob__sub">@lang('Limited time bonus for new players')</span>
                            </div>
                        </div>
                        <span class="ob__btn ob__btn--gold">@lang('Claim Offer') <i class="fas fa-bolt"></i></span>
                    </div>
                </a>
            </div>

            {{-- 3. Daily Rewards --}}
            <div class="swiper-slide">
                <a href="{{ $rewardUrl }}" class="ob ob--daily">
                    <div class="ob__art" aria-hidden="true">
                        <span class="ob__orbit"></span>
                        <span class="ob__medal"><i class="fas fa-medal"></i></span>
                        <span class="ob__dots"></span>
                    </div>
                    <div class="ob__body">
                        <span class="ob__tag">@lang('DAILY REWARDS')</span>
                        <div class="ob__row">
                            <span class="ob__pct">7 DAY</span>
                            <div class="ob__texts">
                                <strong class="ob__title">@lang('Check-in Free Gifts')</strong>
                                <span class="ob__sub">@lang('Login every day & collect rewards')</span>
                            </div>
                        </div>
                        <span class="ob__btn">@lang('Open Rewards') <i class="fas fa-chevron-right"></i></span>
                    </div>
                </a>
            </div>

        </div>
        <div class="swiper-pagination offer-slider__dots"></div>
    </div>
</div>
