{{-- Simple offer banners — site navy / blue / gold, no cropped images --}}
@php
    $depositUrl = auth()->check() ? route('user.deposit.index') : route('user.login');
    $promoUrl = route('user.promotions');
    $rewardUrl = auth()->check() ? route('user.redeem.index') : route('user.login');
@endphp

<div class="slider-wrap">
    <div class="swiper mainSlider offer-slider">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <a href="{{ $depositUrl }}" class="offer-banner offer-banner--deposit">
                    <div class="offer-banner__badge">@lang('HOT')</div>
                    <div class="offer-banner__copy">
                        <span class="offer-banner__kicker">@lang('Deposit Offer')</span>
                        <strong class="offer-banner__title">@lang('First Deposit Bonus')</strong>
                        <span class="offer-banner__sub">@lang('Top up now & unlock extra bonus')</span>
                    </div>
                    <span class="offer-banner__cta">@lang('Deposit')</span>
                    <span class="offer-banner__glow" aria-hidden="true"></span>
                </a>
            </div>

            <div class="swiper-slide">
                <a href="{{ $promoUrl }}" class="offer-banner offer-banner--big">
                    <div class="offer-banner__badge offer-banner__badge--gold">@lang('BIG')</div>
                    <div class="offer-banner__copy">
                        <span class="offer-banner__kicker">@lang('Big Offer')</span>
                        <strong class="offer-banner__title">@lang('Welcome Mega Bonus')</strong>
                        <span class="offer-banner__sub">@lang('Limited time exclusive for new players')</span>
                    </div>
                    <span class="offer-banner__cta offer-banner__cta--gold">@lang('Claim')</span>
                    <span class="offer-banner__glow" aria-hidden="true"></span>
                </a>
            </div>

            <div class="swiper-slide">
                <a href="{{ $rewardUrl }}" class="offer-banner offer-banner--daily">
                    <div class="offer-banner__badge">@lang('DAILY')</div>
                    <div class="offer-banner__copy">
                        <span class="offer-banner__kicker">@lang('Reward Center')</span>
                        <strong class="offer-banner__title">@lang('Daily Rewards')</strong>
                        <span class="offer-banner__sub">@lang('Come back every day for free gifts')</span>
                    </div>
                    <span class="offer-banner__cta">@lang('Open')</span>
                    <span class="offer-banner__glow" aria-hidden="true"></span>
                </a>
            </div>
        </div>
        <div class="swiper-pagination offer-slider__dots"></div>
    </div>
</div>
