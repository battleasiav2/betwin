@extends($activeTemplate . 'layouts.frontend')
@section('content')
<link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;600;700&display=swap" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

<style>
    * { box-sizing: border-box; touch-action: manipulation; }
    ::-webkit-scrollbar { display: none; }
    body { -ms-overflow-style: none; scrollbar-width: none; background-color: #001f1c !important; font-family: 'Hind Siliguri', sans-serif; overflow-x: hidden; width: 100%; position: relative; }
    
    .details-page-wrapper { 
        padding-top: 85px; 
        padding-bottom: 60px; 
        width: 100%; 
    }
    
    .promo-details-container { 
        width: 100%; 
        padding-left: 8px; 
        padding-right: 8px; 
    }

    .details-card { 
        background: #012b27; 
        border: 1px solid #064e46; 
        border-radius: 12px; 
        overflow: hidden; 
        width: 100%; 
        box-shadow: 0 5px 15px rgba(0,0,0,0.4);
    }
    
    .info-grid { 
        display: grid; 
        grid-template-columns: 1fr 1fr; 
        gap: 8px; 
    }
    
    .info-box { 
        background: #001f1c; 
        padding: 10px; 
        border-radius: 8px; 
        border: 1px solid #064e46; 
        text-align: center; 
    }
    
    .info-box span { 
        color: #88a7a4; 
        font-size: 10px; 
        display: block; 
        margin-bottom: 2px; 
        text-transform: uppercase; 
    }
    
    .info-box h4 { 
        color: #fff; 
        margin: 0; 
        font-size: 14px; 
        font-weight: 700; 
    }

    .claim-btn { 
        background: transparent; 
        color: #ffc107 !important; 
        border-radius: 6px; 
        font-size: 15px; 
        font-weight: 700; 
        padding: 12px; 
        border: 1px solid #ffc107; 
        width: 100%; 
        transition: all 0.2s ease; 
        display: block; 
        text-align: center; 
        text-decoration: none; 
        text-transform: uppercase;
    }

    .claim-btn:hover, .claim-btn:active, .claim-btn:focus { 
        background: #ffc107 !important; 
        color: #000 !important; 
        border: 1px solid #ffc107;
        box-shadow: 0 0 15px rgba(255, 193, 7, 0.3);
    }
</style>

<div class="details-page-wrapper">
    <div class="promo-details-container">
        <div class="row justify-content-center g-0">
            <div class="col-12 px-1">
                <div class="details-card">
                    <img src="{{ getImage('assets/images/promotion/' . $promotion->image) }}" style="width: 100% !important; height: auto !important; border-bottom: 1px solid #064e46; display: block;">
                    
                    <div class="p-3">
                        <h4 class="fw-bold mb-3" style="color: #ffc107; line-height: 1.3; font-size: 18px;">{{ __($promotion->title) }}</h4>
                        
                        <div class="info-grid mb-3">
                            <div class="info-box">
                                <span>@lang('Min Deposit')</span>
                                <h4>{{ gs('cur_sym') }}{{ showAmount($promotion->min_limit, currencyFormat: false) }}</h4>
                            </div>
                            <div class="info-box">
                                <span>@lang('Max Bonus')</span>
                                <h4>{{ gs('cur_sym') }}{{ showAmount($promotion->max_bonus, currencyFormat: false) }}</h4>
                            </div>
                            <div class="info-box">
                                <span>@lang('Turnover')</span>
                                <h4 style="color: #00d094;">{{ $promotion->turnover_multiplier }}x</h4>
                            </div>
                            <div class="info-box">
                                <span>@lang('Bonus %')</span>
                                <h4 style="color: #ff9800;">{{ getAmount($promotion->bonus_percent) }}%</h4>
                            </div>
                        </div>

                        <div class="mb-3">
                            <h6 class="text-white border-bottom border-secondary pb-1 mb-2" style="font-size: 14px;">@lang('Description')</h6>
                            <div class="text-light opacity-75 small" style="line-height: 1.5;">@php echo $promotion->description @endphp</div>
                        </div>

                        <div class="p-3 mb-4" style="border: 1px dashed #ffc107; background: rgba(255,193,7,0.03); border-radius: 10px;">
                            <h6 class="text-warning small mb-2" style="font-size: 12px;"><i class="fas fa-info-circle"></i> @lang('Rules')</h6>
                            <ul class="text-light opacity-75 list-unstyled mb-0" style="font-size: 11px; line-height: 1.6;">
                                <li class="mb-1">• @lang('Valid for successful deposits only.')</li>
                                <li class="mb-1">• @lang('Turnover must be completed before withdrawal.')</li>
                                <li>• @lang('One claim per eligible deposit.')</li>
                            </ul>
                        </div>

                        @if($promotion->is_link == 1)
    <a href="{{ $promotion->link ? url($promotion->link) : route('user.deposit.index') }}" class="claim-btn">
        @lang('CLAIM NOW')
    </a>
@endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection