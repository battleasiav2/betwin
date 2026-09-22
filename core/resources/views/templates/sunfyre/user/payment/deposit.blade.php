@extends($activeTemplate . 'layouts.frontend')

@section('content')
<div class="deposit-area pt-5 mt-4 pb-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 col-12">
                <form action="{{ route('user.deposit.insert') }}" method="post" class="deposit-form">
                    @csrf
                    <input type="hidden" name="currency">
                    
                    <div class="gateway-wrapper mb-4">
                        <div class="row g-1 justify-content-center"> 
                            @foreach ($gatewayCurrency as $data)
                            <div class="col-4">
                                <label for="{{ titleToKey($data->name) }}" class="gateway-card">
                                    <input type="radio" name="gateway" id="{{ titleToKey($data->name) }}" value="{{ $data->method_code }}" class="gateway-input" hidden @if($loop->first) checked @endif 
                                        data-gateway='@json($data)'
                                        data-min-amount="{{ showAmount($data->min_amount) }}"
                                        data-max-amount="{{ showAmount($data->max_amount) }}"
                                    >
                                    <div class="card-body-content">
                                        <div class="vip-tag">
                                            <i class="las la-crown"></i> VIP
                                        </div>
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

                    <div class="alert-box mb-3">
                        <p class="text-danger small fw-bold mb-0" style="font-size: 12px; line-height: 1.5;">
                            ! ! ! NOTE : অনুগ্রহ করে আপনার ডিপোজিট করার পরে অবশ্যই আপনার Trx-ID আইডি সাবমিট করবেন। তাহলে খুব দ্রুত আপনার একাউন্টের মধ্যে টাকা যোগ হয়ে যাবে। ⚠️⚠️⚠️
                        </p>
                    </div>

                    <h6 class="section-title text-muted small">Payment channels</h6>
                    <div class="payment-channel mb-3">
                        <span id="selected-channel-name" class="fw-bold text-danger">Select Method</span>
                    </div>

                    @php
                        $promotions = \App\Models\Promotion::where('status', 1)->where('show_deposit', 1)->get();
                    @endphp
                    @if($promotions->count() > 0)
                    <h6 class="section-title text-muted small mt-3">Select Promotion Offer</h6>
                    <div class="promotion-selection-area mb-3">
                        <select name="promotion_id" class="form-control promotion-select" style="background: #fff; border: 1px solid #ced4da; font-weight: 700; border-radius: 8px; height: 45px; color: #333;">
                            <option value="" style="font-weight: 700;">@lang('No Promotion / Normal Deposit')</option>
                            @foreach($promotions as $promo)
                                <option value="{{ $promo->id }}" 
                                    data-bonus="{{ $promo->bonus_percent }}" 
                                    data-min="{{ $promo->min_limit }}" 
                                    data-max-bonus="{{ $promo->max_bonus }}"
                                    style="font-weight: 700;">
                                    {{ __($promo->title) }} ({{ getAmount($promo->bonus_percent) }}% Bonus)
                                </option>
                            @endforeach
                        </select>
                        <div id="bonus-preview-text" class="mt-2 fw-bold" style="display: none; font-size: 12px;"></div>
                    </div>
                    @endif

                    <div class="alert-box mb-3">
                        <p class="text-danger small mb-0" style="font-size: 11px;">
                            ! অনুগ্রহ করে সচেতন থাকুন যে সম্প্রতি ডিপোজিট পরিষেবা প্রদানের জন্য Telegram বা Facebook এর ভান করে অনেক স্ক্যামার এসেছে! ⚠️
                        </p>
                    </div>

                    <h6 class="section-title text-muted small">Deposit Amounts</h6>
                    <div class="amount-grid mb-4">
                        @php 
                            $amounts = [100, 500, 1000, 3000, 5000, 10000, 20000, 25000]; 
                        @endphp
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
    body { background-color: #e8f0fa !important; }
    .section-title { margin-left: 2px; margin-bottom: 5px; font-weight: 600; color: #333; }
    .gateway-card { display: block; cursor: pointer; width: 100%; margin-bottom: 0; padding: 0 1px; }
    .gateway-card .card-body-content { background: #fff; border: 1px solid #e0e0e0; border-radius: 8px; padding: 10px 2px; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 110px; width: 100%; position: relative; overflow: hidden; transition: all 0.2s; }
    .gateway-card .thumb { display: flex; align-items: center; justify-content: center; margin-bottom: 8px; width: 100%; }
    .gateway-card .thumb img { width: 80px; height: 65px; object-fit: contain; }
    .gateway-card .name { font-size: 13px; font-weight: 700; color: #000; line-height: 1.2; text-align: center; width: 100%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .vip-tag { position: absolute; top: 0; left: 0; background: #ff0000; color: #fff; font-size: 9px; padding: 2px 6px; border-bottom-right-radius: 8px; font-weight: bold; z-index: 2; }
    .gateway-input:checked + .card-body-content { border: 1.5px solid #dc3545; box-shadow: 0 2px 8px rgba(220, 53, 69, 0.1); }
    .check-mark { position: absolute; bottom: 3px; right: 3px; color: #dc3545; font-size: 16px; display: none; }
    .gateway-input:checked + .card-body-content .check-mark { display: block; }
    .payment-channel { border: 1px solid #dc3545; border-radius: 6px; padding: 10px; background: #fff; color: #dc3545; }
    .amount-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; }
    .amount-btn { background: #fff; border: 1px solid #ced4da; color: #000000 !important; font-weight: 700; border-radius: 6px; padding: 8px 0; font-size: 13px; transition: 0.3s; }
    .amount-btn.active-amount { background: #333; color: #fff !important; border-color: #333; }
    .amount-btn:hover { border-color: #000; }
    .custom-input-group { background: #fff; border: 1px solid #ced4da; border-radius: 8px; height: 50px; overflow: hidden; padding: 0; }
    .currency-box { background: #e9ecef; height: 100%; width: 50px; display: flex; align-items: center; justify-content: center; border-right: 1px solid #ced4da; }
    .currency-symbol { color: #495057; font-weight: 700; font-size: 20px; }
    .amount-field { border: none; height: 100%; font-size: 18px; font-weight: 700; padding-left: 15px; color: #000 !important; }
    .amount-field:focus { box-shadow: none; background: transparent; }
    .btn-submit { background: transparent !important; border: 1px solid #000000 !important; color: #000000 !important; font-size: 16px; font-weight: 700; border-radius: 25px; padding: 10px; transition: all 0.3s; cursor: not-allowed; opacity: 0.7; }
    .btn-submit.active-btn { background: #ffc107 !important; border: 1px solid #ffc107 !important; color: #000000 !important; cursor: pointer; opacity: 1; box-shadow: 0 4px 10px rgba(255, 193, 7, 0.3); }
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
                    $('#bonus-preview-text').html(`Min. Deposit ${minLimit} {{ gs('cur_sym') }} required for this offer.`).css('color', 'red').fadeIn();
                } else {
                    let bonusAmount = (amount * bonusPercent) / 100;
                    if (maxBonusCap > 0 && bonusAmount > maxBonusCap) {
                        bonusAmount = maxBonusCap;
                    }
                    let total = amount + bonusAmount;
                    $('#bonus-preview-text').html(`You will get ${bonusAmount.toFixed(2)} {{ gs('cur_sym') }} Bonus. Total: ${total.toFixed(2)}`).css('color', 'green').fadeIn();
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
                $('#selected-channel-name').html(gatewayName + ' <span style="color:#333">| TSP</span>');
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