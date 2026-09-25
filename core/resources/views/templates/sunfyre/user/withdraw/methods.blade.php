@extends($activeTemplate . 'layouts.frontend')

@section('content')
<div class="money-page withdraw-area">
    <div class="money-page-bg" style="background-image:url('{{ asset($activeTemplateTrue . 'images/banner/money-page-bg.png') }}')"></div>
    <div class="container money-page-inner">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 col-12">

                <div class="money-hero withdraw-hero">
                    <div class="money-hero-icon"><i class="fas fa-arrow-up-from-bracket"></i></div>
                    <div>
                        <h1 class="money-hero-title">@lang('Withdraw')</h1>
                        <p class="money-hero-sub">@lang('Secure cash-out · PIN protected')</p>
                    </div>
                    <div class="money-hero-bal">
                        <span>@lang('Balance')</span>
                        <strong class="js-live-balance" data-live-balance="full">{{ showAmount(auth()->user()->balance) }}</strong>
                    </div>
                </div>

                @if(auth()->user()->turnover_requirement > 0)
                <div class="money-note danger mb-3">
                    <i class="las la-exclamation-circle"></i>
                    <div>
                        <strong>@lang('Turnover')</strong>
                        <p class="mb-0 mt-1">আপনার বর্তমানে <b>{{ showAmount(auth()->user()->turnover_requirement) }}</b> টার্নওভার বাকি আছে। উইথড্র করতে হলে অবশ্যই গেম খেলে এটি সম্পন্ন করতে হবে।</p>
                    </div>
                </div>
                @endif

                <form action="{{ route('user.withdraw.money') }}" method="post" class="withdraw-form money-panel">
                    @csrf

                    <div class="money-label">@lang('Select Wallet')</div>
                    <div class="gateway-section mb-3">
                        <div class="d-flex gap-2 gateway-scroll">
                            @foreach ($withdrawMethod as $data)
                                @php $isBound = isset($userMethods[$data->id]); @endphp
                                <label class="gateway-item">
                                    <input type="radio" name="method_code" value="{{ $data->id }}"
                                        class="gateway-radio"
                                        data-bound="{{ $isBound ? 1 : 0 }}"
                                        data-wallet="{{ $isBound ? $userMethods[$data->id]->wallet_number : '' }}"
                                        data-min="{{ getAmount($data->min_limit) }}"
                                        data-max="{{ getAmount($data->max_limit) }}"
                                        data-charge-percent="{{ $data->percent_charge }}"
                                        data-charge-fixed="{{ $data->fixed_charge }}"
                                        data-currency="{{ $data->currency }}"
                                        data-rate="{{ $data->rate }}"
                                        data-name="{{ __($data->name) }}"
                                        hidden>

                                    <div class="gateway-box">
                                        <img src="{{ getImage(getFilePath('withdrawMethod') . '/' . $data->image) }}" alt="gateway" class="gateway-img">
                                        <div class="check-overlay">
                                            <i class="las la-check"></i>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="card-area mb-4">
                        <div class="credit-card">
                            <div class="card-bg"></div>
                            <div class="card-content">
                                <div class="d-flex justify-content-between align-items-start w-100" style="height: 35px;">
                                    <img src="" class="card-logo-display" style="display: none;" alt="">
                                </div>

                                <div class="card-middle d-flex flex-column align-items-center justify-content-center flex-grow-1">
                                    <h4 class="card-number-display text-white mb-0" style="display: none;">**** **** ****</h4>

                                    <div class="bind-action-area text-center" style="display: none;">
                                        <button type="button" class="btn-plus bind-trigger pulse-animation">
                                            <i class="las la-plus"></i>
                                        </button>
                                        <p class="text-white mt-2 fw-bold" style="font-size: 11px; letter-spacing: 0.5px;">@lang('Tap to Bind')</p>
                                    </div>

                                    <p class="select-msg text-white text-center" style="opacity: 0.75; font-size: 13px;">@lang('Select a wallet')</p>
                                </div>

                                <div class="card-bottom">
                                    <small class="text-white d-block" style="opacity: 0.85; font-size: 11px;">@lang('Available Balance')</small>
                                    <p class="text-white fw-bold mb-0" style="font-size: 18px;">
                                        <span class="js-live-balance" data-live-balance="full">{{ showAmount(auth()->user()->balance) }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="input-section">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="money-label mb-0">@lang('Withdraw Amount') <span class="text-danger">*</span></label>
                            <a href="{{ route('user.withdraw.pin.change') }}" class="pin-link">
                                <i class="las la-key"></i> @lang('Change PIN?')
                            </a>
                        </div>

                        <div class="form-group mb-3">
                            <div class="d-flex align-items-center custom-input-box {{ auth()->user()->turnover_requirement > 0 ? 'is-locked' : '' }}">
                                <span class="currency-sym">{{ gs('cur_sym') }}</span>
                                <input type="number" step="any" name="amount" class="form-control amount-input border-0 bg-transparent"
                                    placeholder="{{ auth()->user()->turnover_requirement > 0 ? 'Complete turnover' : 'Limit: 100 - 25000' }}"
                                    {{ auth()->user()->turnover_requirement > 0 ? 'readonly' : 'required' }} style="box-shadow: none;">
                            </div>
                            <div class="d-flex justify-content-end mt-1">
                                <small class="charge-display fw-bold"></small>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="money-label">@lang('Transaction PIN')</label>
                            <div class="d-flex justify-content-between gap-2" id="withdraw-pin-container">
                                <input type="tel" class="pin-box form-control" maxlength="1" inputmode="numeric" {{ auth()->user()->turnover_requirement > 0 ? 'disabled' : '' }}>
                                <input type="tel" class="pin-box form-control" maxlength="1" inputmode="numeric" {{ auth()->user()->turnover_requirement > 0 ? 'disabled' : '' }}>
                                <input type="tel" class="pin-box form-control" maxlength="1" inputmode="numeric" {{ auth()->user()->turnover_requirement > 0 ? 'disabled' : '' }}>
                                <input type="tel" class="pin-box form-control" maxlength="1" inputmode="numeric" {{ auth()->user()->turnover_requirement > 0 ? 'disabled' : '' }}>
                            </div>
                            <input type="hidden" name="trans_pin" id="final_pin">
                        </div>

                        <button type="submit" class="btn submit-btn w-100" {{ auth()->user()->turnover_requirement > 0 ? 'disabled' : 'disabled' }}>
                            @lang('CONFIRM WITHDRAW')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade money-modal" id="bindModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title">@lang('Bind Account')</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('user.withdraw.bind') }}" method="post" id="bindAccountForm">
                @csrf
                <input type="hidden" name="method_id" id="bindMethodId">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="money-label">@lang('Wallet Number')</label>
                        <input type="tel" name="wallet_number" class="form-control bind-input"
                            placeholder="Wallet Number" maxlength="11" minlength="11"
                            pattern="01[3-9][0-9]{8}"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                        <small class="hint-text">@lang('Enter 11 digits starting with 0 (e.g., 01700000000)')</small>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="submit" class="btn submit-btn w-100">@lang('Save Account')</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if(!auth()->user()->withdraw_pin)
<div class="modal fade money-modal" id="pinSetupModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 justify-content-center pt-4 pb-0">
                <h5 class="modal-title">@lang('Set Withdraw PIN')</h5>
            </div>
            <div class="modal-body text-center p-4">
                <p class="hint-text mb-4">@lang('Please set a 4-digit PIN for security. You will need this for every withdrawal.')</p>

                <form action="{{ route('user.withdraw.pin.store') }}" method="post" id="pinSetupForm">
                    @csrf

                    <label class="money-label text-start w-100">@lang('New PIN')</label>
                    <div class="d-flex justify-content-between gap-2 mb-3" id="setup-pin-container">
                        <input type="tel" class="pin-box form-control" maxlength="1" inputmode="numeric">
                        <input type="tel" class="pin-box form-control" maxlength="1" inputmode="numeric">
                        <input type="tel" class="pin-box form-control" maxlength="1" inputmode="numeric">
                        <input type="tel" class="pin-box form-control" maxlength="1" inputmode="numeric">
                    </div>
                    <input type="hidden" name="pin" id="final_setup_pin">

                    <label class="money-label text-start w-100">@lang('Confirm PIN')</label>
                    <div class="d-flex justify-content-between gap-2 mb-4" id="confirm-pin-container">
                        <input type="tel" class="pin-box form-control" maxlength="1" inputmode="numeric">
                        <input type="tel" class="pin-box form-control" maxlength="1" inputmode="numeric">
                        <input type="tel" class="pin-box form-control" maxlength="1" inputmode="numeric">
                        <input type="tel" class="pin-box form-control" maxlength="1" inputmode="numeric">
                    </div>
                    <input type="hidden" name="confirm_pin" id="final_confirm_pin">

                    <button type="submit" class="btn submit-btn w-100">@lang('Save PIN')</button>
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
            radial-gradient(ellipse 80% 50% at 50% 0%, rgba(232,184,74,0.12), transparent 55%),
            linear-gradient(180deg, rgba(11,15,20,0.55) 0%, rgba(11,15,20,0.88) 100%);
    }
    .money-page-inner { position: relative; z-index: 1; }

    .money-hero {
        display: flex; align-items: center; gap: 12px;
        margin-bottom: 14px; padding: 14px 16px; border-radius: 16px;
        background: linear-gradient(135deg, rgba(232,184,74,0.16) 0%, rgba(21,27,36,0.92) 55%);
        border: 1px solid rgba(232,184,74,0.28);
        box-shadow: 0 8px 24px rgba(0,0,0,0.25);
    }
    .money-hero-icon {
        width: 44px; height: 44px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        background: rgba(232,184,74,0.18); color: var(--mp-gold);
        font-size: 18px; flex-shrink: 0;
        box-shadow: inset 0 0 0 1px rgba(232,184,74,0.35);
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
        margin: 0 0 8px;
    }
    .money-note {
        display: flex; gap: 10px; align-items: flex-start;
        border-radius: 12px; padding: 12px 14px;
        font-size: 12px; line-height: 1.45; font-weight: 600;
    }
    .money-note i { font-size: 20px; flex-shrink: 0; margin-top: 1px; }
    .money-note.danger {
        background: rgba(220,53,69,0.12);
        border: 1px solid rgba(220,53,69,0.3);
        color: #ff8a96;
    }
    .money-note.danger strong { color: #ffb3bb; display: block; font-size: 13px; }
    .money-note.danger p { color: #ffc9ce; }

    .pin-link {
        font-size: 12px; font-weight: 800; color: var(--mp-gold);
        text-decoration: none;
    }
    .pin-link:hover { color: #f0d078; }

    input:-webkit-autofill,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:focus,
    input:-webkit-autofill:active {
        -webkit-text-fill-color: #e8eef5 !important;
        -webkit-box-shadow: 0 0 0px 1000px #0f1419 inset !important;
        transition: background-color 5000s ease-in-out 0s;
    }

    .gateway-scroll { overflow-x: auto; padding-bottom: 4px; scrollbar-width: none; }
    .gateway-scroll::-webkit-scrollbar { display: none; }
    .gateway-item { margin-bottom: 0; flex: 0 0 auto; cursor: pointer; position: relative; }

    .gateway-box {
        min-width: 118px; height: 58px;
        background: var(--mp-card2);
        border: 1px solid var(--mp-border);
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        position: relative; transition: all 0.2s; padding: 8px; overflow: hidden;
    }
    .gateway-img { max-width: 85%; max-height: 34px; object-fit: contain; }

    .check-overlay {
        position: absolute; inset: 0;
        background: rgba(45,212,168,0.14);
        display: none; align-items: center; justify-content: center;
        border-radius: 12px;
    }
    .check-overlay i {
        font-size: 18px; color: #0b0f14; background: var(--mp-teal);
        border-radius: 50%; padding: 3px;
        box-shadow: 0 4px 10px rgba(45,212,168,0.4);
    }
    .gateway-radio:checked + .gateway-box {
        border: 1.5px solid rgba(45,212,168,0.7);
        background: linear-gradient(180deg, rgba(45,212,168,0.12), var(--mp-card2));
        box-shadow: 0 6px 16px rgba(45,212,168,0.15);
    }
    .gateway-radio:checked + .gateway-box .check-overlay { display: flex; }

    .credit-card {
        width: 100%; height: 190px;
        border-radius: 18px; position: relative; overflow: hidden;
        box-shadow: 0 12px 28px rgba(0,0,0,0.3); margin: 0 auto;
        border: 1px solid rgba(232,184,74,0.25);
    }
    .card-bg {
        position: absolute; inset: 0; z-index: 1;
        background:
            radial-gradient(ellipse 70% 80% at 90% 10%, rgba(45,212,168,0.35), transparent 50%),
            radial-gradient(ellipse 60% 70% at 10% 90%, rgba(232,184,74,0.28), transparent 50%),
            linear-gradient(135deg, #1a2330 0%, #151b24 45%, #0f1a18 100%);
    }
    .card-bg::after {
        content: '';
        position: absolute; width: 160px; height: 160px; right: -30px; top: -40px;
        border-radius: 50%;
        border: 1px solid rgba(255,255,255,0.08);
        box-shadow: 0 0 0 20px rgba(255,255,255,0.03);
    }
    .card-content {
        position: relative; z-index: 2; padding: 16px 20px;
        height: 100%; display: flex; flex-direction: column;
    }
    .card-logo-display {
        height: 30px; padding: 3px 8px;
        background: rgba(11,15,20,0.65); border-radius: 8px;
        border: 1px solid rgba(255,255,255,0.1);
    }
    .card-number-display {
        font-size: 22px !important; letter-spacing: 3px !important;
        font-weight: 700; text-shadow: 0 2px 8px rgba(0,0,0,0.35); font-family: monospace;
        color: #fff !important;
    }

    @keyframes pulse-white {
        0% { box-shadow: 0 0 0 0 rgba(45, 212, 168, 0.55); }
        70% { box-shadow: 0 0 0 10px rgba(45, 212, 168, 0); }
        100% { box-shadow: 0 0 0 0 rgba(45, 212, 168, 0); }
    }
    .pulse-animation { animation: pulse-white 2s infinite; }

    .btn-plus {
        width: 46px; height: 46px; border-radius: 50%;
        background: rgba(45,212,168,0.2); border: 2px dashed rgba(45,212,168,0.7);
        color: var(--mp-teal); font-size: 20px;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto; transition: 0.3s; backdrop-filter: blur(5px);
    }
    .btn-plus:hover { transform: scale(1.05); }

    .custom-input-box {
        background: #0f1419;
        border: 1px solid var(--mp-border);
        border-radius: 14px; overflow: hidden; height: 54px;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .custom-input-box:focus-within {
        border-color: rgba(232,184,74,0.55);
        box-shadow: 0 0 0 3px rgba(232,184,74,0.12);
    }
    .custom-input-box.is-locked { opacity: 0.65; }
    .currency-sym {
        padding: 0 4px 0 14px;
        font-weight: 900; font-size: 18px; color: var(--mp-gold);
    }
    .amount-input {
        background: transparent !important; font-size: 18px; font-weight: 800;
        color: var(--mp-text) !important; height: 100%; border: none !important; padding-left: 0;
    }
    .amount-input::placeholder { color: #6b7788; font-weight: 600; font-size: 13px; }
    .charge-display { font-size: 11px; color: #ff8a96; }

    .pin-box {
        height: 52px; text-align: center; font-size: 22px; font-weight: 900;
        border-radius: 12px; border: 1px solid var(--mp-border);
        background: #0f1419 !important; color: var(--mp-text) !important; transition: 0.25s;
        box-shadow: none !important;
    }
    .pin-box:focus {
        border-color: rgba(232,184,74,0.55);
        box-shadow: 0 0 0 3px rgba(232,184,74,0.12) !important;
    }

    .submit-btn {
        background: linear-gradient(135deg, #e8b84a 0%, #c49a3a 50%, #2dd4a8 160%) !important;
        border: none !important; color: #0b0f14 !important;
        font-size: 14px; font-weight: 900; border-radius: 14px !important;
        padding: 14px !important; letter-spacing: 0.5px;
        box-shadow: 0 8px 22px rgba(232,184,74,0.28);
    }
    .submit-btn:disabled {
        background: rgba(255,255,255,0.06) !important;
        color: var(--mp-muted) !important;
        box-shadow: none; cursor: not-allowed; opacity: 0.75;
    }

    .money-modal .modal-content {
        background: #151b24; border: 1px solid var(--mp-border); border-radius: 18px;
        color: var(--mp-text);
    }
    .money-modal .modal-title { font-weight: 900; color: var(--mp-text); }
    .bind-input {
        background: #0f1419 !important; border: 1px solid var(--mp-border); border-radius: 12px;
        height: 50px; color: var(--mp-text) !important; font-weight: 700;
    }
    .bind-input:focus {
        border-color: rgba(232,184,74,0.55);
        box-shadow: 0 0 0 3px rgba(232,184,74,0.12);
    }
    .hint-text { font-size: 11px; color: var(--mp-muted); font-weight: 600; display: block; margin-top: 6px; }
</style>
@endpush

@push('script')
<script>
    (function($) {
        "use strict";

        @if(!auth()->user()->withdraw_pin)
            var pinModal = new bootstrap.Modal(document.getElementById('pinSetupModal'));
            pinModal.show();
        @endif

        $('#bindAccountForm').on('submit', function(e) {
            let wallet = $('input[name=wallet_number]').val();
            if(wallet.length !== 11 || !wallet.startsWith('0')) {
                e.preventDefault();
                alert('ভুল নম্বর! ১১ ডিজিটের মোবাইল নম্বর দিন যা ০ দিয়ে শুরু।');
                return false;
            }
        });

        function setupPinInputs(containerId, hiddenInputId) {
            const container = $(`#${containerId}`);
            const inputs = container.find('.pin-box');
            const hiddenInput = $(`#${hiddenInputId}`);

            inputs.on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
                if (this.value.length === 1) {
                    $(this).next('.pin-box').focus();
                }
                updateHiddenValue();
            });

            inputs.on('keydown', function(e) {
                if (e.key === 'Backspace' && this.value.length === 0) {
                    $(this).prev('.pin-box').focus();
                }
                setTimeout(updateHiddenValue, 10);
            });

            function updateHiddenValue() {
                let pin = '';
                inputs.each(function() { pin += $(this).val(); });
                hiddenInput.val(pin);
            }
        }

        setupPinInputs('withdraw-pin-container', 'final_pin');
        setupPinInputs('setup-pin-container', 'final_setup_pin');
        setupPinInputs('confirm-pin-container', 'final_confirm_pin');

        let currentMethodId = null;

        $('.gateway-radio').on('change', function() {
            let el = $(this);
            let isBound = el.data('bound') == 1;
            let wallet = el.data('wallet');
            let min = el.data('min');
            let max = el.data('max');
            let cur = el.data('currency');

            let logoSrc = el.next('.gateway-box').find('img').attr('src');

            currentMethodId = el.val();

            $('.amount-input').attr('placeholder', `Limit: ${min} - ${max} ${cur}`);
            $('.amount-input').val('');
            $('.charge-display').text('');

            $('.select-msg').hide();
            $('.card-logo-display').attr('src', logoSrc).fadeIn();

            if(isBound) {
                $('.bind-action-area').hide();
                $('.card-number-display').text(wallet).fadeIn();
                @if(auth()->user()->turnover_requirement <= 0)
                    $('.submit-btn').removeAttr('disabled');
                @endif
            } else {
                $('.card-number-display').hide();
                $('.bind-action-area').css('display', 'flex').fadeIn();
                $('.bind-action-area').addClass('flex-column align-items-center');
                $('.submit-btn').attr('disabled', true);
            }
        });

        $('.bind-trigger').on('click', function() {
            if(currentMethodId) {
                $('#bindMethodId').val(currentMethodId);
                $('#bindModal').modal('show');
            }
        });

        $('.amount-input').on('input', function() {
            let amount = parseFloat($(this).val());
            let el = $('.gateway-radio:checked');

            if(el.length > 0 && amount > 0) {
                let percent = parseFloat(el.data('charge-percent'));
                let fixed = parseFloat(el.data('charge-fixed'));
                let cur = el.data('currency');
                let charge = fixed + (amount * percent / 100);
                $('.charge-display').text(`Charge: ${charge.toFixed(2)} ${cur}`);
            } else {
                $('.charge-display').text('');
            }
        });

    })(jQuery);
</script>
@endpush
