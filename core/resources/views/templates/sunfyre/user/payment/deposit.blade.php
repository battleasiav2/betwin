@extends($activeTemplate . 'layouts.frontend')

@section('content')
<div class="money-page deposit-area">
    <div class="money-page-bg" style="background-image:url('{{ asset($activeTemplateTrue . 'images/banner/money-page-bg.png') }}')"></div>
    <div class="container money-page-inner">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 col-12">

                <div class="money-hero deposit-hero">
                    <div class="money-hero-icon"><i class="fas fa-plus-circle"></i></div>
                    <div>
                        <h1 class="money-hero-title">@lang('Deposit')</h1>
                        <p class="money-hero-sub">@lang('Fast top-up · secure wallet')</p>
                    </div>
                    <div class="money-hero-bal">
                        <span>@lang('Balance')</span>
                        <strong class="js-live-balance" data-live-balance="full">{{ showAmount(auth()->user()->balance) }}</strong>
                    </div>
                </div>

                <form action="{{ route('user.deposit.insert') }}" method="post" class="deposit-form money-panel">
                    @csrf
                    <input type="hidden" name="currency">

                    <div class="money-label">@lang('Payment Method')</div>
                    <div class="gateway-wrapper mb-3">
                        <div class="row g-2 justify-content-center">
                            @foreach ($gatewayCurrency as $data)
                            <div class="col-4">
                                <label for="{{ titleToKey($data->name) }}" class="gateway-card">
                                    <input type="radio" name="gateway" id="{{ titleToKey($data->name) }}" value="{{ $data->method_code }}" class="gateway-input" hidden @if($loop->first) checked @endif
                                        data-gateway='@json($data)'
                                        data-min-amount="{{ showAmount($data->min_amount) }}"
                                        data-max-amount="{{ showAmount($data->max_amount) }}"
                                    >
                                    <div class="card-body-content">
                                        <div class="vip-tag"><i class="las la-crown"></i> VIP</div>
                                        <div class="thumb">
                                            <img src="{{ getImage(getFilePath('gateway') . '/' . $data->method->image) }}" alt="{{ $data->name }}">
                                        </div>
                                        <span class="name">{{ __($data->name) }}</span>
                                        <div class="check-mark"><i class="las la-check-circle"></i></div>
                                    </div>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="money-note warn mb-3">
                        <i class="fas fa-info-circle"></i>
                        <p>অনুগ্রহ করে ডিপোজিটের পরে অবশ্যই Trx-ID সাবমিট করবেন — তাহলে দ্রুত ব্যালেন্স যোগ হবে।</p>
                    </div>

                    <div class="money-label">@lang('Payment Channel')</div>
                    <div class="payment-channel mb-3">
                        <i class="fas fa-wallet"></i>
                        <span id="selected-channel-name" class="fw-bold">Select Method</span>
                    </div>

                    @php
                        $promotions = \App\Models\Promotion::where('status', 1)->where('show_deposit', 1)->get();
                    @endphp
                    @if($promotions->count() > 0)
                    <div class="money-label mt-1">@lang('Select Promotion Offer')</div>
                    <div class="promotion-selection-area mb-3">
                        <select name="promotion_id" class="form-control promotion-select money-select">
                            <option value="">@lang('No Promotion / Normal Deposit')</option>
                            @foreach($promotions as $promo)
                                <option value="{{ $promo->id }}"
                                    data-bonus="{{ $promo->bonus_percent }}"
                                    data-min="{{ $promo->min_limit }}"
                                    data-max-bonus="{{ $promo->max_bonus }}">
                                    {{ __($promo->title) }} ({{ getAmount($promo->bonus_percent) }}% Bonus)
                                </option>
                            @endforeach
                        </select>
                        <div id="bonus-preview-text" class="mt-2 fw-bold bonus-preview" style="display: none; font-size: 12px;"></div>
                    </div>
                    @endif

                    <div class="money-note danger mb-3">
                        <i class="fas fa-shield-alt"></i>
                        <p>সতর্ক থাকুন — Telegram/Facebook এ ভুয়া ডিপোজিট সার্ভিস দিয়ে স্ক্যাম হতে পারে!</p>
                    </div>

                    <div class="money-label">@lang('Deposit Amounts')</div>
                    <div class="amount-grid mb-3">
                        @php $amounts = [100, 500, 1000, 3000, 5000, 10000, 20000, 25000]; @endphp
                        @foreach($amounts as $amt)
                        <div class="amount-item">
                            <button type="button" class="btn btn-sm amount-btn w-100" data-amount="{{ $amt }}">{{ $amt }}</button>
                        </div>
                        @endforeach
                    </div>

                    <div class="form-group mb-4">
                        <div class="custom-input-group d-flex align-items-center">
                            <div class="currency-box">
                                <span class="currency-symbol">{{ gs('cur_sym') }}</span>
                            </div>
                            <input type="number" step="any" name="amount" class="form-control amount-field" placeholder="100 - 25,000" value="{{ old('amount') }}" autocomplete="off">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-submit w-100" disabled>@lang('Next')</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
<style>
    :root {
        --mp-bg: #0b0f14;
        --mp-card: #151b24;
        --mp-card2: #1a2330;
        --mp-teal: #2dd4a8;
        --mp-gold: #e8b84a;
        --mp-text: #e8eef5;
        --mp-muted: #8b97a8;
        --mp-border: rgba(255,255,255,0.08);
    }
    body { background-color: var(--mp-bg) !important; color: var(--mp-text) !important; }
    .money-page {
        position: relative;
        min-height: 100vh;
        padding: 78px 0 40px;
        overflow: hidden;
    }
    .money-page-bg {
        position: fixed;
        inset: 0;
        background-size: cover;
        background-position: center;
        opacity: 0.22;
        pointer-events: none;
        z-index: 0;
    }
    .money-page-bg::after {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse 80% 50% at 50% 0%, rgba(45,212,168,0.12), transparent 55%),
            linear-gradient(180deg, rgba(11,15,20,0.55) 0%, rgba(11,15,20,0.88) 100%);
    }
    .money-page-inner { position: relative; z-index: 1; }
    .money-hero {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 14px;
        padding: 14px 16px;
        border-radius: 16px;
        background: linear-gradient(135deg, rgba(45,212,168,0.16) 0%, rgba(21,27,36,0.92) 55%);
        border: 1px solid rgba(45,212,168,0.28);
        box-shadow: 0 8px 24px rgba(0,0,0,0.25);
    }
    .money-hero-icon {
        width: 44px; height: 44px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        background: rgba(45,212,168,0.18); color: var(--mp-teal);
        font-size: 20px; flex-shrink: 0;
        box-shadow: inset 0 0 0 1px rgba(45,212,168,0.35);
    }
    .money-hero-title {
        margin: 0; font-size: 18px; font-weight: 900; color: var(--mp-text); letter-spacing: 0.3px;
    }
    .money-hero-sub { margin: 2px 0 0; font-size: 11px; color: var(--mp-muted); font-weight: 600; }
    .money-hero-bal {
        margin-left: auto; text-align: right;
    }
    .money-hero-bal span { display: block; font-size: 10px; color: var(--mp-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px; }
    .money-hero-bal strong {
        font-size: 15px; font-weight: 900;
        background: linear-gradient(90deg, #f0d078, #2dd4a8);
        -webkit-background-clip: text; background-clip: text; color: transparent;
    }
    .money-panel {
        background: rgba(21,27,36,0.82);
        backdrop-filter: blur(10px);
        border: 1px solid var(--mp-border);
        border-radius: 18px;
        padding: 16px 14px 18px;
        box-shadow: 0 12px 32px rgba(0,0,0,0.28);
    }
    .money-label {
        font-size: 11px; font-weight: 800; color: var(--mp-muted);
        text-transform: uppercase; letter-spacing: 0.6px;
        margin: 0 2px 8px;
    }
    .money-note {
        display: flex; gap: 10px; align-items: flex-start;
        border-radius: 12px; padding: 10px 12px;
        font-size: 11px; line-height: 1.45; font-weight: 600;
    }
    .money-note i { margin-top: 2px; flex-shrink: 0; font-size: 14px; }
    .money-note p { margin: 0; }
    .money-note.warn {
        background: rgba(232,184,74,0.1);
        border: 1px solid rgba(232,184,74,0.28);
        color: #f0d078;
    }
    .money-note.danger {
        background: rgba(220,53,69,0.1);
        border: 1px solid rgba(220,53,69,0.28);
        color: #ff8a96;
    }
    .gateway-card { display: block; cursor: pointer; width: 100%; margin: 0; }
    .gateway-card .card-body-content {
        background: var(--mp-card2);
        border: 1px solid var(--mp-border);
        border-radius: 12px;
        padding: 10px 4px 8px;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        height: 112px; width: 100%; position: relative; overflow: hidden;
        transition: border-color 0.2s, box-shadow 0.2s, transform 0.15s;
    }
    .gateway-card .thumb { display: flex; align-items: center; justify-content: center; margin-bottom: 6px; width: 100%; }
    .gateway-card .thumb img { width: 72px; height: 52px; object-fit: contain; }
    .gateway-card .name {
        font-size: 12px; font-weight: 800; color: var(--mp-text);
        line-height: 1.2; text-align: center; width: 100%;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis; padding: 0 4px;
    }
    .vip-tag {
        position: absolute; top: 0; left: 0;
        background: linear-gradient(90deg, #e8b84a, #2dd4a8);
        color: #0b0f14; font-size: 9px; padding: 2px 7px;
        border-bottom-right-radius: 8px; font-weight: 900; z-index: 2;
    }
    .gateway-input:checked + .card-body-content {
        border-color: rgba(45,212,168,0.65);
        box-shadow: 0 0 0 1px rgba(45,212,168,0.25), 0 8px 18px rgba(45,212,168,0.15);
        background: linear-gradient(180deg, rgba(45,212,168,0.12), var(--mp-card2));
    }
    .check-mark {
        position: absolute; bottom: 4px; right: 5px;
        color: var(--mp-teal); font-size: 16px; display: none;
    }
    .gateway-input:checked + .card-body-content .check-mark { display: block; }
    .payment-channel {
        display: flex; align-items: center; gap: 8px;
        border: 1px solid rgba(232,184,74,0.35);
        border-radius: 12px; padding: 12px 14px;
        background: linear-gradient(135deg, rgba(232,184,74,0.12), rgba(21,27,36,0.9));
        color: var(--mp-gold); font-size: 13px;
    }
    .payment-channel i { color: var(--mp-gold); }
    .money-select {
        background: #0f1419 !important;
        border: 1px solid var(--mp-border) !important;
        font-weight: 700; border-radius: 12px !important;
        height: 48px; color: var(--mp-text) !important;
        box-shadow: none !important;
    }
    .money-select:focus {
        border-color: rgba(232,184,74,0.5) !important;
        box-shadow: 0 0 0 3px rgba(232,184,74,0.12) !important;
    }
    .bonus-preview { color: var(--mp-teal); }
    .amount-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; }
    .amount-btn {
        background: var(--mp-card2);
        border: 1px solid var(--mp-border);
        color: var(--mp-text) !important;
        font-weight: 800; border-radius: 10px; padding: 10px 0; font-size: 13px;
        transition: 0.2s;
    }
    .amount-btn.active-amount {
        background: linear-gradient(135deg, rgba(232,184,74,0.28), rgba(21,27,36,0.95));
        color: var(--mp-gold) !important;
        border-color: rgba(232,184,74,0.5);
        box-shadow: 0 4px 12px rgba(232,184,74,0.15);
    }
    .custom-input-group {
        background: #0f1419;
        border: 1px solid var(--mp-border);
        border-radius: 14px; height: 54px; overflow: hidden; padding: 0;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .custom-input-group:focus-within {
        border-color: rgba(45,212,168,0.5);
        box-shadow: 0 0 0 3px rgba(45,212,168,0.12);
    }
    .currency-box {
        background: rgba(232,184,74,0.12);
        height: 100%; width: 52px;
        display: flex; align-items: center; justify-content: center;
        border-right: 1px solid var(--mp-border);
    }
    .currency-symbol { color: var(--mp-gold); font-weight: 900; font-size: 18px; }
    .amount-field {
        border: none !important; height: 100%; font-size: 18px; font-weight: 800;
        padding-left: 14px; color: var(--mp-text) !important; background: transparent !important;
        box-shadow: none !important;
    }
    .amount-field:focus { box-shadow: none !important; background: transparent !important; }
    .btn-submit {
        background: rgba(255,255,255,0.04) !important;
        border: 1px solid rgba(255,255,255,0.12) !important;
        color: var(--mp-muted) !important;
        font-size: 15px; font-weight: 900; border-radius: 14px; padding: 13px;
        transition: all 0.25s; cursor: not-allowed; opacity: 0.75; letter-spacing: 0.4px;
    }
    .btn-submit.active-btn {
        background: linear-gradient(135deg, #2dd4a8 0%, #1fa88a 45%, #e8b84a 160%) !important;
        border: none !important; color: #0b0f14 !important;
        cursor: pointer; opacity: 1;
        box-shadow: 0 8px 22px rgba(45,212,168,0.35);
    }
</style>
@endpush

@push('script')
<script>
    "use strict";
    (function($) {
        var amount = 0;
        var gateway = null;

        $('.amount-field').on('input', function(e) {
            amount = parseFloat($(this).val() || 0);
            calculation();
            updateBonusPreview();
        });

        $('.promotion-select').on('change', function() {
            updateBonusPreview();
            calculation();
        });

        function updateBonusPreview() {
            let selected = $('.promotion-select option:selected');
            let bonusPercent = parseFloat(selected.data('bonus'));
            let minLimit = parseFloat(selected.data('min'));
            let maxBonusCap = parseFloat(selected.data('max-bonus'));

            if (selected.val() != "" && amount > 0) {
                if (amount < minLimit) {
                    $('#bonus-preview-text').html(`Min. Deposit ${minLimit} {{ gs('cur_sym') }} required for this offer.`).css('color', '#ff8a96').fadeIn();
                } else {
                    let bonusAmount = (amount * bonusPercent) / 100;
                    if (maxBonusCap > 0 && bonusAmount > maxBonusCap) {
                        bonusAmount = maxBonusCap;
                    }
                    let total = amount + bonusAmount;
                    $('#bonus-preview-text').html(`You will get ${bonusAmount.toFixed(2)} {{ gs('cur_sym') }} Bonus. Total: ${total.toFixed(2)}`).css('color', '#2dd4a8').fadeIn();
                }
            } else {
                $('#bonus-preview-text').hide();
            }
        }

        $('.amount-btn').on('click', function() {
            var val = $(this).data('amount');
            $('.amount-field').val(val).trigger('input');
            $('.amount-btn').removeClass('active-amount');
            $(this).addClass('active-amount');
        });

        $('.gateway-input').on('change', function(e) {
            gatewayChange();
        });

        function gatewayChange() {
            let gatewayElement = $('.gateway-input:checked');
            if (gatewayElement.length > 0) {
                gateway = gatewayElement.data('gateway');
                let gatewayName = gatewayElement.next('.card-body-content').find('.name').text();
                $('#selected-channel-name').html(gatewayName + ' <span style="color:#8b97a8">| TSP</span>');
                calculation();
            }
        }

        function calculation() {
            if (!gateway) return;
            $("input[name=currency]").val(gateway.currency);

            let selectedPromo = $('.promotion-select option:selected');
            let minLimit = parseFloat(selectedPromo.data('min') || 0);

            let isGatewayOk = (amount >= Number(gateway.min_amount) && amount <= Number(gateway.max_amount));
            let isPromoOk = (selectedPromo.val() == "" || amount >= minLimit);

            if (isGatewayOk && isPromoOk) {
                $(".btn-submit").removeAttr('disabled').addClass('active-btn');
            } else {
                $(".btn-submit").attr('disabled', true).removeClass('active-btn');
            }
        }

        gatewayChange();
    })(jQuery);
</script>
@endpush
