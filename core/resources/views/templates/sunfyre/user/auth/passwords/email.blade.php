@extends($activeTemplate . 'layouts.app')

@section('app')
    <section class="gate-page">
        <div class="gate-card">
            <a href="{{ route('user.login') }}" class="gate-back"><i class="fas fa-chevron-left"></i> @lang('Back')</a>
            <div class="gate-logo"><img src="{{ siteLogo() }}" alt="{{ gs('site_name') }}"></div>
            <div class="gate-kicker">Account</div>
            <h1 class="gate-title">@lang('Recover Account')</h1>
            <p class="gate-text">@lang('Provide your email or username to find your account.')</p>

            <form method="POST" action="{{ route('user.password.email') }}" class="verify-gcaptcha">
                @csrf
                <div class="gate-field">
                    <label>@lang('Email or Username')</label>
                    <div class="gate-input">
                        <i class="fas fa-user"></i>
                        <input type="text" name="value" class="form-control" placeholder="Email or username" value="{{ old('value') }}" required autocomplete="off" autofocus>
                    </div>
                </div>
                <x-captcha />
                <button type="submit" class="gate-btn">@lang('Submit')</button>
                <p class="gate-note">@lang('Remember your password?') <a href="{{ route('user.login') }}">@lang('Login')</a></p>
            </form>
        </div>
    </section>
@endsection

@push('style')
    @include($activeTemplate . 'partials.auth_gate_style')
@endpush
