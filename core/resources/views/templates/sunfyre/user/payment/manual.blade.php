@extends($activeTemplate . 'layouts.frontend')

@section('content')
@php
    $walletNumber = manualGatewayWalletNumber($gateway);
    $hasDynamicForm = $gateway->form && !empty((array) ($gateway->form->form_data ?? []));
@endphp
<div class="money-page deposit-area">
    <div class="money-page-bg" style="background-image:url('{{ asset($activeTemplateTrue . 'images/banner/money-page-bg.png') }}')"></div>
    <div class="container money-page-inner">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 col-12">

                <div class="money-hero deposit-hero">
                    <div class="money-hero-icon"><i class="fas fa-mobile-alt"></i></div>
                    <div>
                        <h1 class="money-hero-title">{{ __($method->name) }}</h1>
                        <p class="money-hero-sub">@lang('Send money · submit TRX ID')</p>
                    </div>
                </div>

                <div class="money-panel mb-3">
                    <div class="money-label">@lang('Pay Amount')</div>
                    <div class="manual-amount-box">
                        <strong>{{ showAmount($data->final_amount, currencyFormat: false) }} {{ $data->method_currency }}</strong>
                        <span>@lang('Deposit') {{ showAmount($data->amount) }}</span>
                    </div>

                    @if($walletNumber)
                    <div class="money-label mt-3">@lang('Send Money To')</div>
                    <div class="manual-wallet-box">
                        <div>
                            <small>{{ __($method->name) }} @lang('Number')</small>
                            <div class="manual-wallet-number" id="walletNumberText">{{ $walletNumber }}</div>
                        </div>
                        <button type="button" class="manual-copy-btn" id="copyWalletBtn">
                            <i class="fas fa-copy"></i> @lang('Copy')
                        </button>
                    </div>
                    @else
                    <div class="money-note warn mt-3">
                        <i class="fas fa-exclamation-triangle"></i>
                        <p>@lang('Wallet number not set yet. Please contact support.')</p>
                    </div>
                    @endif

                    @if($data->gateway->description)
                    <div class="manual-instruction mt-3">
                        @php echo $data->gateway->description @endphp
                    </div>
                    @endif
                </div>

                <form action="{{ route('user.deposit.manual.update') }}" method="POST" class="money-panel disableSubmission" enctype="multipart/form-data">
                    @csrf
                    <div class="money-label">@lang('After Payment')</div>
                    <p class="manual-hint">@lang('Pay the amount above, then enter the TRX ID from SMS and submit for admin approval.')</p>

                    @if($hasDynamicForm)
                        <x-viser-form identifier="id" identifierValue="{{ $gateway->form_id }}" />
                    @else
                        <div class="form-group mb-3">
                            <label class="money-label">@lang('TRX ID')</label>
                            <input type="text" name="trx_id" class="form-control money-select" value="{{ old('trx_id') }}" placeholder="@lang('Enter transaction ID')" required autocomplete="off">
                        </div>
                    @endif

                    <button type="submit" class="btn btn--base w-100" @disabled(!$walletNumber)>@lang('Submit for Approval')</button>
                    <a href="{{ route('user.deposit.index') }}" class="btn btn-outline--base w-100 mt-2">@lang('Back')</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
<style>
.manual-amount-box {
    display: flex; flex-direction: column; gap: 4px;
    padding: 14px 16px; border-radius: 12px;
    background: rgba(45,212,168,0.1); border: 1px solid rgba(45,212,168,0.28);
}
.manual-amount-box strong { font-size: 22px; color: #2dd4a8; }
.manual-amount-box span { font-size: 12px; color: #8b97a8; }
.manual-wallet-box {
    display: flex; align-items: center; justify-content: space-between; gap: 12px;
    padding: 14px 16px; border-radius: 12px;
    background: rgba(232,184,74,0.1); border: 1px solid rgba(232,184,74,0.3);
}
.manual-wallet-box small { display: block; color: #8b97a8; font-size: 11px; margin-bottom: 4px; }
.manual-wallet-number { font-size: 22px; font-weight: 700; color: #e8b84a; letter-spacing: 0.5px; }
.manual-copy-btn {
    border: 1px solid rgba(232,184,74,0.4); background: transparent; color: #e8b84a;
    border-radius: 999px; padding: 8px 14px; font-size: 12px; font-weight: 600; white-space: nowrap;
}
.manual-instruction { font-size: 13px; color: #a8b3c2; line-height: 1.5; }
.manual-hint { font-size: 13px; color: #8b97a8; margin-bottom: 14px; }
</style>
@endpush

@push('script')
<script>
(function () {
    const btn = document.getElementById('copyWalletBtn');
    const text = document.getElementById('walletNumberText');
    if (!btn || !text) return;
    btn.addEventListener('click', async function () {
        try {
            await navigator.clipboard.writeText(text.textContent.trim());
            btn.innerHTML = '<i class="fas fa-check"></i> Copied';
            setTimeout(() => { btn.innerHTML = '<i class="fas fa-copy"></i> Copy'; }, 1500);
        } catch (e) {
            const range = document.createRange();
            range.selectNode(text);
            window.getSelection().removeAllRanges();
            window.getSelection().addRange(range);
            document.execCommand('copy');
            btn.innerHTML = '<i class="fas fa-check"></i> Copied';
            setTimeout(() => { btn.innerHTML = '<i class="fas fa-copy"></i> Copy'; }, 1500);
        }
    });
})();
</script>
@endpush
