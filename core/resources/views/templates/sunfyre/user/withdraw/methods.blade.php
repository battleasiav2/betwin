@extends($activeTemplate . 'layouts.frontend')

@section('content')
@php
    $payMethods = $withdrawMethod->filter(function ($method) {
        $name = strtolower($method->name);
        return str_contains($name, 'bkash') || str_contains($name, 'nagad');
    })->values();
    $locked = auth()->user()->turnover_requirement > 0;
@endphp
<div class="wd-page">
    <div class="wd-wrap">
        <div class="wd-hero">
            <div>
                <h1>Withdraw</h1>
                <p>bKash বা Nagad সিলেক্ট করুন</p>
            </div>
            <div class="wd-bal">
                <span>Balance</span>
                <strong class="js-live-balance" data-live-balance="full">{{ showAmount(auth()->user()->balance) }}</strong>
            </div>
        </div>

        @if($locked)
        <div class="wd-alert">
            টার্নওভার বাকি <b>{{ showAmount(auth()->user()->turnover_requirement) }}</b>। গেম খেলে শেষ না করলে উইথড্র হবে না।
        </div>
        @endif

        <form action="{{ route('user.withdraw.money') }}" method="post" class="withdraw-form wd-card">
            @csrf
            <div class="wd-label">Select</div>
            <div class="wd-picks">
                @forelse($payMethods as $data)
                    @php $slug = str_contains(strtolower($data->name), 'nagad') ? 'nagad' : 'bkash'; @endphp
                    <label class="wd-pick wd-{{ $slug }}">
                        <input type="radio" name="method_code" value="{{ $data->id }}" class="gateway-radio"
                            data-min="{{ getAmount($data->min_limit) }}"
                            data-max="{{ getAmount($data->max_limit) }}"
                            data-charge-percent="{{ $data->percent_charge }}"
                            data-charge-fixed="{{ $data->fixed_charge }}"
                            data-currency="{{ $data->currency }}"
                            data-name="{{ $slug }}"
                            @if($locked) disabled @endif>
                        <span class="wd-mark"></span>
                        <span class="wd-brand">{{ $slug === 'nagad' ? 'Nagad' : 'bKash' }}</span>
                        <small>Personal</small>
                    </label>
                @empty
                    <p class="wd-empty">bKash বা Nagad এখন চালু নেই।</p>
                @endforelse
            </div>

            <div class="wd-label">Amount</div>
            <div class="wd-field {{ $locked ? 'is-locked' : '' }}">
                <span>{{ gs('cur_sym') }}</span>
                <input type="number" step="any" name="amount" class="amount-input" placeholder="Amount" {{ $locked ? 'readonly' : 'required' }}>
            </div>
            <small class="wd-meta" id="limitText"></small>
            <small class="wd-charge charge-display"></small>

            <div id="payoutBlock" hidden>
                <label class="wd-label" id="payoutLabel" for="payout_number">Enter your personal number</label>
                <div class="wd-field {{ $locked ? 'is-locked' : '' }}">
                    <span><i class="fas fa-mobile-alt"></i></span>
                    <input type="tel" name="payout_number" id="payout_number" placeholder="01XXXXXXXXX" maxlength="11" inputmode="numeric" autocomplete="off" {{ $locked ? 'readonly' : 'required' }}>
                </div>
                <small class="wd-meta">এই নম্বর অ্যাডমিন দেখবে। অ্যাপ্রুভ করলে তবেই উইথড্র হবে।</small>
            </div>

            <div class="wd-pin-head">
                <label class="wd-label">Transaction PIN</label>
                <a href="{{ route('user.withdraw.pin.change') }}">Change PIN</a>
            </div>
            <div class="wd-pins" id="withdraw-pin-container">
                <input type="tel" class="pin-box" maxlength="1" inputmode="numeric" {{ $locked ? 'disabled' : '' }}>
                <input type="tel" class="pin-box" maxlength="1" inputmode="numeric" {{ $locked ? 'disabled' : '' }}>
                <input type="tel" class="pin-box" maxlength="1" inputmode="numeric" {{ $locked ? 'disabled' : '' }}>
                <input type="tel" class="pin-box" maxlength="1" inputmode="numeric" {{ $locked ? 'disabled' : '' }}>
            </div>
            <input type="hidden" name="trans_pin" id="final_pin">

            <button type="submit" class="wd-submit confirm-withdraw" @if($locked || $payMethods->isEmpty()) disabled @endif>CONFIRM WITHDRAW</button>
        </form>
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
                    <label class="wd-label">New PIN</label>
                    <div class="wd-pins" id="setup-pin-container">
                        <input type="tel" class="pin-box" maxlength="1" inputmode="numeric">
                        <input type="tel" class="pin-box" maxlength="1" inputmode="numeric">
                        <input type="tel" class="pin-box" maxlength="1" inputmode="numeric">
                        <input type="tel" class="pin-box" maxlength="1" inputmode="numeric">
                    </div>
                    <input type="hidden" name="pin" id="final_setup_pin">
                    <label class="wd-label">Confirm PIN</label>
                    <div class="wd-pins" id="confirm-pin-container">
                        <input type="tel" class="pin-box" maxlength="1" inputmode="numeric">
                        <input type="tel" class="pin-box" maxlength="1" inputmode="numeric">
                        <input type="tel" class="pin-box" maxlength="1" inputmode="numeric">
                        <input type="tel" class="pin-box" maxlength="1" inputmode="numeric">
                    </div>
                    <input type="hidden" name="confirm_pin" id="final_confirm_pin">
                    <button type="submit" class="wd-submit">Save PIN</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('style')
<style>
    .wd-page { min-height: 100vh; background: #0b0f14; color: #e8eef5; padding: 78px 14px 96px; }
    .wd-wrap { max-width: 460px; margin: 0 auto; }
    .wd-hero, .wd-card, .wd-alert {
        background: rgba(21, 27, 36, 0.94);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 18px;
    }
    .wd-hero { display: flex; justify-content: space-between; align-items: center; padding: 16px; margin-bottom: 12px; }
    .wd-hero h1 { margin: 0; font-size: 20px; font-weight: 800; }
    .wd-hero p, .wd-bal span, .wd-meta, .wd-charge { color: #8b97a8; font-size: 12px; }
    .wd-hero p { margin: 4px 0 0; }
    .wd-bal { text-align: right; }
    .wd-bal strong { display: block; color: #2dd4a8; font-size: 16px; }
    .wd-alert { padding: 12px 14px; margin-bottom: 12px; color: #ffb4bc; font-size: 13px; border-color: rgba(255,138,150,0.35); }
    .wd-card { padding: 16px; overflow: hidden; }
    .wd-label { display: block; margin: 14px 0 8px; color: #8b97a8; font-size: 12px; font-weight: 700; letter-spacing: 0.4px; text-transform: uppercase; }
    .wd-card > .wd-label:first-child { margin-top: 0; }
    .wd-picks { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
    .wd-pick {
        position: relative; display: flex; flex-direction: column; justify-content: center; min-height: 78px;
        padding: 14px; border-radius: 14px; border: 1px solid rgba(255,255,255,0.1); background: #0b0f14; cursor: pointer;
    }
    .wd-pick input { position: absolute; opacity: 0; pointer-events: none; }
    .wd-brand { font-size: 18px; font-weight: 800; }
    .wd-pick small { color: #8b97a8; }
    .wd-bkash .wd-brand { color: #ff4b8b; }
    .wd-nagad .wd-brand { color: #ff9f2e; }
    .wd-pick:has(input:checked) { border-color: currentColor; }
    .wd-bkash:has(input:checked) { border-color: #e2136e; box-shadow: 0 0 0 1px #e2136e inset; }
    .wd-nagad:has(input:checked) { border-color: #f6921e; box-shadow: 0 0 0 1px #f6921e inset; }
    .wd-mark {
        position: absolute; top: 10px; right: 10px; width: 16px; height: 16px; border-radius: 50%;
        border: 1px solid rgba(255,255,255,0.2);
    }
    .wd-pick:has(input:checked) .wd-mark { background: #2dd4a8; border-color: #2dd4a8; }
    .wd-field {
        display: flex; align-items: center; gap: 8px; background: #0b0f14;
        border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 0 12px; height: 52px;
    }
    .wd-field span { color: #e8b84a; font-weight: 800; }
    .wd-field input {
        width: 100%; border: 0; outline: 0; background: transparent; color: #e8eef5; font-size: 16px; font-weight: 700;
    }
    .wd-field.is-locked { opacity: 0.6; }
    .wd-meta, .wd-charge { display: block; margin-top: 6px; }
    .wd-charge { color: #ff8a96; }
    .wd-pin-head { display: flex; justify-content: space-between; align-items: center; }
    .wd-pin-head a { color: #e8b84a; font-size: 12px; text-decoration: none; }
    .wd-pins { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 8px; width: 100%; }
    .pin-box {
        width: 100%; min-width: 0; box-sizing: border-box; height: 52px; text-align: center; font-size: 22px; font-weight: 800; border-radius: 12px;
        border: 1px solid rgba(255,255,255,0.1); background: #0b0f14; color: #e8eef5; padding: 0;
    }
    .wd-submit {
        width: 100%; margin-top: 16px; border: 0; border-radius: 14px; padding: 14px;
        background: linear-gradient(135deg, #e8b84a, #c9962e); color: #1a1203; font-weight: 800; letter-spacing: 0.4px;
    }
    .wd-submit:disabled { background: rgba(255,255,255,0.08); color: #8b97a8; }
    .wd-empty { color: #8b97a8; margin: 0; }
    .wd-modal { background: #151b24; color: #e8eef5; border-radius: 18px; border: 1px solid rgba(255,255,255,0.08); }
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

        $('.gateway-radio').on('change', function () {
            const el = $(this);
            const name = String(el.data('name') || '');
            const title = name === 'nagad' ? 'Nagad' : 'bKash';
            const prompt = 'Enter your ' + title + ' personal number';
            $('#payoutLabel').text(prompt);
            $('#payout_number').attr('placeholder', prompt);
            $('#payoutBlock').prop('hidden', false);
            $('#limitText').text('Limit ' + el.data('min') + ' - ' + el.data('max') + ' ' + el.data('currency'));
            @if(!$locked)
                $('.confirm-withdraw').prop('disabled', false);
            @endif
        });

        $('input[name=amount]').on('input', function () {
            const amount = parseFloat($(this).val());
            const el = $('.gateway-radio:checked');
            if (el.length && amount > 0) {
                const charge = parseFloat(el.data('charge-fixed')) + (amount * parseFloat(el.data('charge-percent')) / 100);
                $('.charge-display').text('Charge: ' + charge.toFixed(2) + ' ' + el.data('currency'));
            } else {
                $('.charge-display').text('');
            }
        });

        $('.withdraw-form').on('submit', function (e) {
            if (!$('.gateway-radio:checked').length) {
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
