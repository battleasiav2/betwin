@extends($activeTemplate . 'layouts.app')

@section('app')
    <section class="gate-page">
        <div class="gate-card">
            <a href="{{ route('home') }}" class="gate-back"><i class="fas fa-chevron-left"></i> @lang('Back')</a>
            <div class="gate-logo"><img src="{{ siteLogo() }}" alt="{{ gs('site_name') }}"></div>
            <div class="gate-kicker">Security</div>
            <h1 class="gate-title">@lang('Verify Code')</h1>
            <form action="{{ route('user.password.verify.code') }}" method="POST" class="submit-form">
                @csrf
                <p class="gate-text">
                    @lang('A 6 digit verification code sent to your email address')<br>
                    <span class="text-white">{{ showEmailAddress($email) }}</span>
                </p>
                <input type="hidden" name="email" value="{{ $email }}">
                @include($activeTemplate . 'partials.verification_code')
                <button type="submit" class="gate-btn">@lang('Submit')</button>
                <p class="gate-note">
                    @lang('Please check including your Junk/Spam Folder. If not found, you can')
                    <a href="{{ route('user.password.request') }}">@lang('Try to send again')</a>
                </p>
            </form>
        </div>
    </section>
@endsection

@push('style')
    @include($activeTemplate . 'partials.auth_gate_style')
@endpush
