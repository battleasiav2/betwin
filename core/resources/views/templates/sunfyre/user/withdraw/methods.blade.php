@extends($activeTemplate . 'layouts.frontend')

@section('content')
@php
    $locked = auth()->user()->turnover_requirement > 0;
    $payMethods = $withdrawMethod->values();
@endphp
<div class="money-page withdraw-area">
    <div class="money-page-bg" style="background-image:url('{{ asset($activeTemplateTrue . 'images/banner/money-page-bg.png') }}')"></div>
    <div class="container money-page-inner">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 col-12">

                <div class="money-hero withdraw-hero">
                    <div class="money-hero-icon"><i class="fas fa-arrow-up-from-bracket"></i></div>
                    <div>
                        <h1 class="money-hero-title">@lang('Withdraw')</h1>
                        <p class="money-hero-sub">bKash বা Nagad সিলেক্ট করুন</p>
                    </div>
                    <div class="money-hero-bal">
                        <span>@lang('Balance')</span>
                        <strong class="js-live-balance" data-live-balance="full">{{ showAmount(auth()->user()->balance) }}</strong>
                    </div>
                </div>

                @if($locked)
                <div class="money-note danger mb-3">
                    <i class="las la-exclamation-circle"></i>
                    <p>টার্নওভার বাকি <b>{{ showAmount(auth()->user()->turnover_requirement) }}</b>। গেম খেলে শেষ না করলে উইথড্র হবে না।</p>
                </div>
                @endif

                <form action="{{ route('user.withdraw.money') }}" method="post" class="withdraw-form money-panel">
                    @csrf

                    <div class="money-label">@lang('Payment Method')</div>
                    <div class="gateway-wrapper mb-3">
                        <div class="row g-2 justify-content-center">
                            @forelse($payMethods as $data)
                                @php
                                    $compact = preg_replace('/[^a-z]/', '', strtolower($data->name));
                                    if (str_contains($compact, 'nagad')) {
                                        $title = 'Nagad';
                                    } elseif (str_contains($compact, 'bkash')) {
                                        $title = 'bKash';
                                    } else {
                                        $title = __($data->name);
                                    }
                                @endphp
                                <div class="col-6 col-sm-4">
                                    <label class="gateway-card">
                                        <input type="radio" name="method_code" value="{{ $data->id }}" class="gateway-input" hidden
                                            @if($loop->first && !$locked) checked @endif
                                            data-min="{{ getAmount($data->min_limit) }}"
                                            data-max="{{ getAmount($data->max_limit) }}"
                                            data-charge-percent="{{ $data->percent_charge }}"
                                            data-charge-fixed="{{ $data->fixed_charge }}"
                                            data-currency="{{ $data->currency }}"
                                            data-title="{{ $title }}"
                                            @if($locked) disabled @endif>
                                        <div class="card-body-content">
                                            <div class="vip-tag"><i class="las la-crown"></i> VIP</div>
                                            <div class="thumb">
                                                <img src="{{ getImage(getFilePath('withdrawMethod') . '/' . $data->image) }}" alt="{{ $title }}">
                                            </div>
                                            <span class="name">{{ $title }}</span>
                                            <div class="check-mark"><i class="las la-check-circle"></i></div>
                                        </div>
                                    </label>
                                </div>
                            @empty
                                <p class="wd-empty">এখন কোনো উইথড্র মেথড চালু নেই। অ্যাডমিন থেকে bKash আর Nagad অন করুন।</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="money-label">@lang('Payment Channel')</div>
                    <div class="payment-channel mb-3">
                        <i class="fas fa-wallet"></i>
                        <span id="selected-channel-name" class="fw-bold">Select Method</span>
                    </div>

                    <div class="money-label">@lang('Withdraw Amount')</div>
                    <div class="custom-input-group d-flex align-items-center mb-1 {{ $locked ? 'is-locked' : '' }}">
                        <div class="currency-box"><span class="currency-symbol">{{ gs('cur_sym') }}</span></div>
                        <input type="number" step="any" name="amount" class="form-control amount-field" placeholder="Amount" autocomplete="off" {{ $locked ? 'readonly' : 'required' }}>
                    </div>
                    <small class="wd-meta" id="limitText"></small>
                    <small class="wd-charge charge-display"></small>

                    <div id="payoutBlock" @if($payMethods->isEmpty()) hidden @endif>
                        <label class="money-label" id="payoutLabel" for="payout_number">Enter your personal number</label>
                        <div class="custom-input-group d-flex align-items-center {{ $locked ? 'is-locked' : '' }}">
                            <div class="currency-box"><span class="currency-symbol"><i class="fas fa-mobile-alt"></i></span></div>
                            <input type="tel" name="payout_number" id="payout_number" class="form-control amount-field" placeholder="01XXXXXXXXX" maxlength="11" inputmode="numeric" autocomplete="off" {{ $locked ? 'readonly' : 'required' }}>
                        </div>
                        <small class="wd-meta">এই নম্বর অ্যাডমিন দেখবে। অ্যাপ্রুভ করলে তবেই উইথড্র হবে।</small>
                    </div>

                    <div class="wd-pin-head">
                        <label class="money-label">@lang('Transaction PIN')</label>
                        <a href="{{ route('user.withdraw.pin.change') }}">Change PIN</a>
                    </div>
                    <div class="wd-pins" id="withdraw-pin-container">
                        <input type="tel" class="pin-box" maxlength="1" inputmode="numeric" {{ $locked ? 'disabled' : '' }}>
                        <input type="tel" class="pin-box" maxlength="1" inputmode="numeric" {{ $locked ? 'disabled' : '' }}>
                        <input type="tel" class="pin-box" maxlength="1" inputmode="numeric" {{ $locked ? 'disabled' : '' }}>
                        <input type="tel" class="pin-box" maxlength="1" inputmode="numeric" {{ $locked ? 'disabled' : '' }}>
                    </div>
                    <input type="hidden" name="trans_pin" id="final_pin">

                    <button type="submit" class="btn btn-submit confirm-withdraw w-100" @if($locked || $payMethods->isEmpty()) disabled @endif>@lang('CONFIRM WITHDRAW')</button>
                </form>
            </div>
        </div>
    </div>
</div>

@if(!auth()->user()->withdraw_pin)
<div class="modal fade" id="pinSetupModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content wd-modal">
            <div class="modal-body">
                <h2>Set Withdraw PIN</h2>
                <p>প্রতিবার উইথড্রের জন্য ৪ সংখ্যার পিন লাগবে।</p>
                <form action="{{ route('user.withdraw.pin.store') }}" method="post">
                    @csrf
                    <label class="money-label">New PIN</label>
                    <div class="wd-pins" id="setup-pin-container">
                        <input type="tel" class="pin-box" maxlength="1" inputmode="numeric">
                        <input type="tel" class="pin-box" maxlength="1" inputmode="numeric">
                        <input type="tel" class="pin-box" maxlength="1" inputmode="numeric">
                        <input type="tel" class="pin-box" maxlength="1" inputmode="numeric">
                    </div>
                    <input type="hidden" name="pin" id="final_setup_pin">
                    <label class="money-label">Confirm PIN</label>
                    <div class="wd-pins" id="confirm-pin-container">
                        <input type="tel" class="pin-box" maxlength="1" inputmode="numeric">
                        <input type="tel" class="pin-box" maxlength="1" inputmode="numeric">
                        <input type="tel" class="pin-box" maxlength="1" inputmode="numeric">
                        <input type="tel" class="pin-box" maxlength="1" inputmode="numeric">
                    </div>
                    <input type="hidden" name="confirm_pin" id="final_confirm_pin">
                    <button type="submit" class="btn btn-submit active-btn w-100">Save PIN</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
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
    .money-page { position: relative; min-height: 100vh; padding: 78px 0 40px; overflow: hidden; }
    .money-page-bg {
        position: fixed; inset: 0; background-size: cover; background-position: center;
        opacity: 0.22; pointer-events: none; z-index: 0;
    }
    .money-page-bg::after {
        content: ''; position: absolute; inset: 0;
        background: radial-gradient(ellipse 80% 50% at 50% 0%, rgba(232,184,74,0.12), transparent 55%),
            linear-gradient(180deg, rgba(11,15,20,0.55) 0%, rgba(11,15,20,0.88) 100%);
    }
    .money-page-inner { position: relative; z-index: 1; }
    .money-hero {
        display: flex; align-items: center; gap: 12px; margin-bottom: 14px; padding: 14px 16px; border-radius: 16px;
        background: linear-gradient(135deg, rgba(232,184,74,0.16) 0%, rgba(21,27,36,0.92) 55%);
        border: 1px solid rgba(232,184,74,0.28); box-shadow: 0 8px 24px rgba(0,0,0,0.25);
    }
    .money-hero-icon {
        width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center;
        background: rgba(232,184,74,0.18); color: var(--mp-gold); font-size: 18px; flex-shrink: 0;
    }
    .money-hero-title { margin: 0; font-size: 18px; font-weight: 900; color: var(--mp-text); }
    .money-hero-sub { margin: 2px 0 0; font-size: 11px; color: var(--mp-muted); font-weight: 600; }
    .money-hero-bal { margin-left: auto; text-align: right; }
    .money-hero-bal span { display: block; font-size: 10px; color: var(--mp-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px; }
    .money-hero-bal strong {
        font-size: 15px; font-weight: 900;
        background: linear-gradient(90deg, #f0d078, #2dd4a8);
        -webkit-background-clip: text; background-clip: text; color: transparent;
    }
    .money-panel {
        background: rgba(21,27,36,0.82); backdrop-filter: blur(10px);
        border: 1px solid var(--mp-border); border-radius: 18px; padding: 16px 14px 18px;
        box-shadow: 0 12px 32px rgba(0,0,0,0.28);
    }
    .money-label {
        font-size: 11px; font-weight: 800; color: var(--mp-muted);
        text-transform: uppercase; letter-spacing: 0.6px; margin: 14px 2px 8px;
    }
    .money-panel > .money-label:first-child { margin-top: 0; }
    .money-note {
        display: flex; gap: 10px; align-items: flex-start; border-radius: 12px; padding: 10px 12px;
        font-size: 11px; line-height: 1.45; font-weight: 600;
    }
    .money-note p { margin: 0; }
    .money-note.danger { background: rgba(220,53,69,0.1); border: 1px solid rgba(220,53,69,0.28); color: #ff8a96; }
    .gateway-card { display: block; cursor: pointer; width: 100%; margin: 0; }
    .gateway-card .card-body-content {
        background: var(--mp-card2); border: 1px solid var(--mp-border); border-radius: 12px;
        padding: 18px 8px 16px; display: flex; flex-direction: column; align-items: center; justify-content: center;
        min-height: 118px; width: 100%; position: relative; overflow: hidden;
    }
    .gateway-card .thumb { display: flex; align-items: center; justify-content: center; margin-bottom: 6px; width: 100%; }
    .gateway-card .thumb img { width: 72px; height: 52px; object-fit: contain; }
    .gateway-card .name {
        font-size: 12px; font-weight: 800; color: var(--mp-text); line-height: 1.2; text-align: center; width: 100%;
    }
    @media (max-width: 575.98px) {
        .gateway-card .name { font-size: 13px; }
        .gateway-card .thumb img { width: 84px; height: 56px; }
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
    .check-mark { position: absolute; bottom: 4px; right: 5px; color: var(--mp-teal); font-size: 16px; display: none; }
    .gateway-input:checked + .card-body-content .check-mark { display: block; }
    .payment-channel {
        display: flex; align-items: center; gap: 8px;
        border: 1px solid rgba(232,184,74,0.35); border-radius: 12px; padding: 12px 14px;
        background: linear-gradient(135deg, rgba(232,184,74,0.12), rgba(21,27,36,0.9));
        color: var(--mp-gold); font-size: 13px;
    }
    .custom-input-group {
        background: #0f1419; border: 1px solid var(--mp-border); border-radius: 14px; height: 54px; overflow: hidden;
    }
    .custom-input-group.is-locked { opacity: 0.6; }
    .currency-box {
        background: rgba(232,184,74,0.12); height: 100%; width: 52px;
        display: flex; align-items: center; justify-content: center; border-right: 1px solid var(--mp-border);
    }
    .currency-symbol { color: var(--mp-gold); font-weight: 900; font-size: 18px; }
    .amount-field {
        border: none !important; height: 100%; font-size: 18px; font-weight: 800;
        padding-left: 14px; color: var(--mp-text) !important; background: transparent !important; box-shadow: none !important;
    }
    .wd-meta, .wd-charge { display: block; margin-top: 6px; color: #8b97a8; font-size: 12px; }
    .wd-charge { color: #ff8a96; }
    .wd-pin-head { display: flex; justify-content: space-between; align-items: flex-end; }
    .wd-pin-head a { color: #e8b84a; font-size: 12px; text-decoration: none; margin-bottom: 8px; }
    .wd-pins { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 8px; width: 100%; }
    .pin-box {
        width: 100%; min-width: 0; box-sizing: border-box; height: 52px; text-align: center; font-size: 22px; font-weight: 800;
        border-radius: 12px; border: 1px solid var(--mp-border); background: #0f1419; color: var(--mp-text); padding: 0;
    }
    .btn-submit {
        margin-top: 16px; background: rgba(255,255,255,0.04) !important; border: 1px solid rgba(255,255,255,0.12) !important;
        color: var(--mp-muted) !important; font-size: 15px; font-weight: 900; border-radius: 14px; padding: 13px;
    }
    .btn-submit.active-btn, .confirm-withdraw:not(:disabled) {
        background: linear-gradient(135deg, #2dd4a8 0%, #1fa88a 45%, #e8b84a 160%) !important;
        border: none !important; color: #0b0f14 !important;
        box-shadow: 0 8px 22px rgba(45,212,168,0.35);
    }
    .wd-empty { color: var(--mp-muted); margin: 0; }
    .wd-modal { background: #151b24; color: #e8eef5; border-radius: 18px; border: 1px solid var(--mp-border); }
    .wd-modal h2 { margin: 0 0 8px; font-size: 18px; }
    .wd-modal p { color: #8b97a8; font-size: 13px; }
</style>
@endpush

@push('script')
<script>
    (function ($) {
        "use strict";

        @if(!auth()->user()->withdraw_pin)
            new bootstrap.Modal(document.getElementById('pinSetupModal')).show();
        @endif

        function setupPinInputs(containerId, hiddenInputId) {
            const container = $('#' + containerId);
            const inputs = container.find('.pin-box');
            const hiddenInput = $('#' + hiddenInputId);
            if (!container.length || !hiddenInput.length) return;
            inputs.on('input', function () {
                this.value = this.value.replace(/[^0-9]/g, '');
                if (this.value.length === 1) $(this).next('.pin-box').focus();
                updateHiddenValue();
            });
            inputs.on('keydown', function (e) {
                if (e.key === 'Backspace' && this.value.length === 0) $(this).prev('.pin-box').focus();
                setTimeout(updateHiddenValue, 10);
            });
            function updateHiddenValue() {
                let pin = '';
                inputs.each(function () { pin += $(this).val(); });
                hiddenInput.val(pin);
            }
        }

        setupPinInputs('withdraw-pin-container', 'final_pin');
        setupPinInputs('setup-pin-container', 'final_setup_pin');
        setupPinInputs('confirm-pin-container', 'final_confirm_pin');

        $('#payout_number').on('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);
        });

        function showMethod(el) {
            const title = String(el.data('title') || 'wallet');
            const prompt = 'Enter your ' + title + ' personal number';
            $('#selected-channel-name').text(title + ' | Personal');
            $('#payoutLabel').text(prompt);
            $('#payout_number').attr('placeholder', prompt);
            $('#payoutBlock').prop('hidden', false);
            $('#limitText').text('Limit ' + el.data('min') + ' - ' + el.data('max') + ' ' + el.data('currency'));
            @if(!$locked)
                $('.confirm-withdraw').prop('disabled', false);
            @endif
        }

        $('.gateway-input').on('change', function () {
            showMethod($(this));
        });

        const selected = $('.gateway-input:checked');
        if (selected.length) showMethod(selected);

        $('input[name=amount]').on('input', function () {
            const amount = parseFloat($(this).val());
            const el = $('.gateway-input:checked');
            if (el.length && amount > 0) {
                const charge = parseFloat(el.data('charge-fixed')) + (amount * parseFloat(el.data('charge-percent')) / 100);
                $('.charge-display').text('Charge: ' + charge.toFixed(2) + ' ' + el.data('currency'));
            } else {
                $('.charge-display').text('');
            }
        });

        $('.withdraw-form').on('submit', function (e) {
            if (!$('.gateway-input:checked').length) {
                e.preventDefault();
                alert('bKash অথবা Nagad সিলেক্ট করুন।');
                return false;
            }
            const wallet = ($('#payout_number').val() || '').replace(/\D/g, '');
            $('#payout_number').val(wallet);
            if (!/^01[3-9][0-9]{8}$/.test(wallet)) {
                e.preventDefault();
                alert('সঠিক ১১ ডিজিটের পার্সোনাল নম্বর দিন।');
                return false;
            }
            if (($('#final_pin').val() || '').length !== 4) {
                e.preventDefault();
                alert('৪ সংখ্যার ট্রানজেকশন পিন দিন।');
                return false;
            }
        });
    })(jQuery);
</script>
@endpush
