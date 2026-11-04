@extends($activeTemplate . 'layouts.app')

@section('app')
    <section class="gate-page">
        <div class="gate-card">
            <a href="{{ route('user.login') }}" class="gate-back"><i class="fas fa-chevron-left"></i> @lang('Back')</a>
            <div class="gate-logo"><img src="{{ siteLogo() }}" alt="{{ gs('site_name') }}"></div>
            <div class="gate-kicker">Account</div>
            <h1 class="gate-title">Forgot Password</h1>
            <p class="gate-text">Enter the email you used during sign up. We will send a reset code to that email.</p>

            <form method="POST" action="{{ route('user.password.email') }}" class="verify-gcaptcha">
                @csrf
                <div class="gate-field">
                    <label>Email</label>
                    <div class="gate-input">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="value" class="form-control" placeholder="Enter your email" value="{{ old('value') }}" required autocomplete="email" autofocus>
                    </div>
                </div>
                <x-captcha />
                <button type="submit" class="gate-btn">Send Reset Code</button>
                <p class="gate-note">Remember your password? <a href="{{ route('user.login') }}">Login</a></p>
            </form>
        </div>
    </section>
@endsection

@push('style')
    @include($activeTemplate . 'partials.auth_gate_style')
@endpush
