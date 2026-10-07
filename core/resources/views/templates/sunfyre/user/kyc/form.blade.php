@extends($activeTemplate . 'layouts.master')
@section('content')
    <section class="gate-page">
        <div class="gate-card" style="max-width: 520px;">
            <a href="{{ route('user.home') }}" class="gate-back"><i class="fas fa-chevron-left"></i> @lang('Back')</a>
            <div class="gate-logo"><img src="{{ siteLogo() }}" alt="{{ gs('site_name') }}"></div>
            <div class="gate-kicker">Verify</div>
            <h1 class="gate-title">@lang('KYC Verification')</h1>
            <p class="gate-text">@lang('Please provide the required information for identity verification.')</p>

            <form action="{{ route('user.kyc.submit') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="text-start viser-form-data">
                    <x-viser-form identifier="act" identifierValue="kyc" />
                </div>
                <button class="gate-btn" type="submit">@lang('Submit')</button>
            </form>
        </div>
    </section>
@endsection

@push('style')
    @include($activeTemplate . 'partials.auth_gate_style')
@endpush
