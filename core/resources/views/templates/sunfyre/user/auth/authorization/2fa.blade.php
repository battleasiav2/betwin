@extends($activeTemplate . 'layouts.app')

@section('app')
    <section class="gate-page">
        <div class="gate-card">
            <a href="{{ route('home') }}" class="gate-back"><i class="fas fa-chevron-left"></i> @lang('Back')</a>
            <div class="gate-logo"><img src="{{ siteLogo() }}" alt="{{ gs('site_name') }}"></div>
            <div class="gate-kicker">Security</div>
            <h1 class="gate-title">@lang('2FA Verification')</h1>
            <form action="{{ route('user.2fa.verify') }}" method="POST" class="submit-form">
                @csrf
                <p class="gate-text">@lang('Please enter the 6-digit Google Authenticator code to secure your account.')</p>
                @include($activeTemplate . 'partials.verification_code')
                <button type="submit" class="gate-btn">@lang('Submit')</button>
            </form>
        </div>
    </section>
@endsection

@push('style')
    @include($activeTemplate . 'partials.auth_gate_style')
@endpush
