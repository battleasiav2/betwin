@extends($activeTemplate . 'layouts.frontend')

@section('content')
<div class="container" style="padding-top: 80px; padding-bottom: 50px;">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8 col-12">
            
            {{-- Turnover Requirement Alert --}}
            @if(auth()->user()->turnover_requirement > 0)
                <div class="alert alert-danger shadow-sm border-0 mb-4" style="border-radius: 12px; background-color: #fff5f5; border: 1px solid #ffcccc !important;">
                    <div class="d-flex align-items-center">
                        <i class="las la-exclamation-circle fs-3 me-2 text-danger"></i>
                        <strong class="text-danger" style="font-size: 14px;">@lang('Turnover ')</strong>
                    </div>
                    <p class="mb-0 mt-1 text-dark small">
                        আপনার বর্তমানে <b>{{ showAmount(auth()->user()->turnover_requirement) }}</b> টার্নওভার বাকি আছে। উইথড্র করতে হলে অবশ্যই গেম খেলে এটি সম্পন্ন করতে হবে।
                    </p>
                </div>
            @endif
            
            <form action="{{ route('user.withdraw.money') }}" method="post" class="withdraw-form">
                @csrf
                
                <div class="gateway-section mb-3">
                    <h6 class="text-dark fw-bold mb-2 ms-1 small">@lang('Select Wallet')</h6>
                    <div class="d-flex gap-2 gateway-scroll ps-1">
                        @foreach ($withdrawMethod as $data)
                            @php
                                $isBound = isset($userMethods[$data->id]);
                            @endphp
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
                                <img src="" class="card-logo-display" style="display: none;">
                            </div>

                            <div class="card-middle d-flex flex-column align-items-center justify-content-center flex-grow-1">
                                <h4 class="card-number-display text-white mb-0" style="display: none;">**** **** ****</h4>
                                
                                <div class="bind-action-area text-center" style="display: none;">
                                    <button type="button" class="btn-plus bind-trigger pulse-animation">
                                        <i class="las la-plus"></i>
                                    </button>
                                    <p class="text-white mt-2 fw-bold" style="font-size: 11px; letter-spacing: 0.5px;">@lang('Tap to Bind')</p>
                                </div>
                                
                                <p class="select-msg text-white text-center" style="opacity: 0.7; font-size: 13px;">@lang('Select a wallet')</p>
                            </div>

                            <div class="card-bottom">
                                <small class="text-white d-block" style="opacity: 0.9; font-size: 11px;">@lang('Available Balance')</small>
                                <p class="text-white fw-bold mb-0" style="font-size: 18px;">
                                    {{ showAmount(auth()->user()->balance) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="input-section p-2">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="fw-bold text-dark small mb-0">@lang('Withdraw Amount') <span class="text-danger">*</span></label>
                        <a href="{{ route('user.withdraw.pin.change') }}" class="small text--base text-decoration-none fw-bold" style="font-size: 12px;">
                            <i class="las la-key"></i> @lang('Change PIN?')
                        </a>
                    </div>

                    <div class="form-group mb-3">
                        <div class="d-flex align-items-center custom-input-box shadow-sm {{ auth()->user()->turnover_requirement > 0 ? 'bg-light' : '' }}">
                            <span class="ps-3 pe-2 fw-bold text-black fs-5">{{ gs('cur_sym') }}</span>
                            <input type="number" step="any" name="amount" class="form-control amount-input text-black border-0 bg-transparent" 
                                placeholder="{{ auth()->user()->turnover_requirement > 0 ? 'Complete turnover' : 'Limit: 100 - 25000' }}" 
                                {{ auth()->user()->turnover_requirement > 0 ? 'readonly' : 'required' }} style="box-shadow: none;">
                        </div>
                        <div class="d-flex justify-content-end mt-1">
                            <small class="text-danger charge-display fw-bold" style="font-size: 11px;"></small>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="fw-bold text-dark small mb-2">@lang('Transaction PIN')</label>
                        <div class="d-flex justify-content-between gap-2" id="withdraw-pin-container">
                            <input type="tel" class="pin-box form-control shadow-sm text-black" maxlength="1" inputmode="numeric" {{ auth()->user()->turnover_requirement > 0 ? 'disabled' : '' }}>
                            <input type="tel" class="pin-box form-control shadow-sm text-black" maxlength="1" inputmode="numeric" {{ auth()->user()->turnover_requirement > 0 ? 'disabled' : '' }}>
                            <input type="tel" class="pin-box form-control shadow-sm text-black" maxlength="1" inputmode="numeric" {{ auth()->user()->turnover_requirement > 0 ? 'disabled' : '' }}>
                            <input type="tel" class="pin-box form-control shadow-sm text-black" maxlength="1" inputmode="numeric" {{ auth()->user()->turnover_requirement > 0 ? 'disabled' : '' }}>
                        </div>
                        <input type="hidden" name="trans_pin" id="final_pin">
                    </div>

                    <button type="submit" class="btn btn--base w-100 py-3 rounded-pill fw-bold submit-btn shadow-sm" {{ auth()->user()->turnover_requirement > 0 ? 'disabled' : 'disabled' }}>
                        @lang('CONFIRM WITHDRAW')
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="bindModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark">@lang('Bind Account')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('user.withdraw.bind') }}" method="post" id="bindAccountForm">
                @csrf
                <input type="hidden" name="method_id" id="bindMethodId">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="fw-bold text-dark mb-2 small">@lang('Wallet Number')</label>
                        <input type="tel" name="wallet_number" class="form-control bind-input text-black" 
                            placeholder="Wallet Number" maxlength="11" minlength="11" 
                            pattern="01[3-9][0-9]{8}" 
                            oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                        <small class="text-muted mt-1 d-block" style="font-size: 10px;">@lang('Enter 11 digits starting with 0 (e.g., 01700000000)')</small>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="submit" class="btn btn--base w-100 rounded-pill">@lang('Save Account')</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if(!auth()->user()->withdraw_pin)
<div class="modal fade" id="pinSetupModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 justify-content-center pt-4 pb-0">
                <h5 class="modal-title fw-bold text-dark">@lang('Set Withdraw PIN')</h5>
            </div>
            <div class="modal-body text-center p-4">
                <p class="text-muted small mb-4">@lang('Please set a 4-digit PIN for security. You will need this for every withdrawal.')</p>
                
                <form action="{{ route('user.withdraw.pin.store') }}" method="post" id="pinSetupForm">
                    @csrf
                    
                    <label class="fw-bold text-dark small mb-2 text-start w-100">@lang('New PIN')</label>
                    <div class="d-flex justify-content-between gap-2 mb-3" id="setup-pin-container">
                        <input type="tel" class="pin-box form-control shadow-sm text-black" maxlength="1" inputmode="numeric">
                        <input type="tel" class="pin-box form-control shadow-sm text-black" maxlength="1" inputmode="numeric">
                        <input type="tel" class="pin-box form-control shadow-sm text-black" maxlength="1" inputmode="numeric">
                        <input type="tel" class="pin-box form-control shadow-sm text-black" maxlength="1" inputmode="numeric">
                    </div>
                    <input type="hidden" name="pin" id="final_setup_pin">

                    <label class="fw-bold text-dark small mb-2 text-start w-100">@lang('Confirm PIN')</label>
                    <div class="d-flex justify-content-between gap-2 mb-4" id="confirm-pin-container">
                        <input type="tel" class="pin-box form-control shadow-sm text-black" maxlength="1" inputmode="numeric">
                        <input type="tel" class="pin-box form-control shadow-sm text-black" maxlength="1" inputmode="numeric">
                        <input type="tel" class="pin-box form-control shadow-sm text-black" maxlength="1" inputmode="numeric">
                        <input type="tel" class="pin-box form-control shadow-sm text-black" maxlength="1" inputmode="numeric">
                    </div>
                    <input type="hidden" name="confirm_pin" id="final_confirm_pin">

                    <button type="submit" class="btn btn--base w-100 rounded-pill fw-bold">@lang('Save PIN')</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@push('style')
<style>
    body { background-color: #e8f0fa !important; color: #000000 !important; }
    .text-black { color: #000000 !important; font-weight: 600; }
    
    input:-webkit-autofill,
    input:-webkit-autofill:hover, 
    input:-webkit-autofill:focus, 
    input:-webkit-autofill:active{
        -webkit-text-fill-color: #000 !important;
        -webkit-box-shadow: 0 0 0px 1000px #ffffff inset !important;
        transition: background-color 5000s ease-in-out 0s;
    }

    .gateway-scroll { overflow-x: auto; padding-bottom: 5px; scrollbar-width: none; }
    .gateway-scroll::-webkit-scrollbar { display: none; }
    .gateway-item { margin-bottom: 0; flex: 0 0 auto; cursor: pointer; position: relative; }
    
    .gateway-box {
        min-width: 120px; height: 60px;
        background: #f8f9fa; border: 1px solid #e9ecef;
        border-radius: 10px; display: flex; align-items: center; justify-content: center;
        position: relative; transition: all 0.2s; padding: 8px; overflow: hidden;
    }
    .gateway-img { max-width: 85%; max-height: 35px; object-fit: contain; }
    
    .check-overlay {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(40, 199, 111, 0.1); display: none; align-items: center; justify-content: center;
        border-radius: 10px;
    }
    .check-overlay i {
        font-size: 20px; color: #fff; background: #28c76f;
        border-radius: 50%; padding: 4px; box-shadow: 0 4px 10px rgba(40, 199, 111, 0.4);
    }
    .gateway-radio:checked + .gateway-box { border: 2px solid #28c76f; background: #fff; }
    .gateway-radio:checked + .gateway-box .check-overlay { display: flex; }

    .credit-card {
        width: 100%; height: 190px;
        border-radius: 18px; position: relative; overflow: hidden;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15); margin: 0 auto;
    }
    .card-bg {
        position: absolute; width: 100%; height: 100%;
        background: linear-gradient(135deg, #3b2c85 0%, #5643cc 100%); z-index: 1;
    }
    .card-content {
        position: relative; z-index: 2; padding: 15px 20px;
        height: 100%; display: flex; flex-direction: column;
    }
    .card-logo-display {
        height: 30px; padding: 3px 6px;
        background: #fff; border-radius: 5px;
    }
    .card-number-display {
        font-size: 26px !important; letter-spacing: 4px !important;
        font-weight: 600; text-shadow: 0 2px 4px rgba(0,0,0,0.3); font-family: monospace;
    }

    @keyframes pulse-white {
        0% { box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.7); }
        70% { box-shadow: 0 0 0 8px rgba(255, 255, 255, 0); }
        100% { box-shadow: 0 0 0 0 rgba(255, 255, 255, 0); }
    }
    .pulse-animation { animation: pulse-white 2s infinite; }

    .btn-plus {
        width: 45px; height: 45px; border-radius: 50%;
        background: rgba(255,255,255,0.2); border: 2px dashed #fff;
        color: #fff; font-size: 20px;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto; transition: 0.3s; backdrop-filter: blur(5px);
    }
    .btn-plus:hover { transform: scale(1.05); }

    .custom-input-box {
        background: #fff; border: 1px solid #ced4da; border-radius: 12px; overflow: hidden; height: 50px;
    }
    .custom-input-box:focus-within {
        border-color: #4634ff; box-shadow: 0 0 0 3px rgba(70, 52, 255, 0.1) !important;
    }
    .amount-input {
        background: transparent !important; font-size: 18px; font-weight: 600;
        color: #000 !important; height: 100%; border: none !important; padding-left: 0;
    }
    .amount-input::placeholder { color: #aaa; font-weight: normal; font-size: 14px; }

    .pin-box {
        height: 50px; text-align: center; font-size: 22px; font-weight: bold;
        border-radius: 10px; border: 1px solid #dfe1e5;
        background: #fff !important; color: #000 !important; transition: 0.3s;
    }
    .pin-box:focus {
        border-color: #4634ff; box-shadow: 0 0 0 3px rgba(70, 52, 255, 0.15) !important;
    }

    .bind-input {
        background: #fff !important; border: 1px solid #ced4da; border-radius: 12px;
        height: 50px; color: #000 !important; font-weight: 600;
    }
    .bind-input:focus {
        border-color: #4634ff; box-shadow: 0 0 0 3px rgba(70, 52, 255, 0.1);
    }
    .bg-light {
        background-color: #e8f0fa !important;
    }
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

        // Bind Form Validation
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
            let name = el.data('name');
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