@extends($activeTemplate . 'layouts.app')

@section('app')
    <section class="gate-page">
        <div class="gate-card">
            <a href="{{ route('user.login') }}" class="gate-back"><i class="fas fa-chevron-left"></i> @lang('Back')</a>
            <div class="gate-logo"><img src="{{ siteLogo() }}" alt="{{ gs('site_name') }}"></div>
            <div class="gate-kicker">Security</div>
            <h1 class="gate-title">@lang('Reset Password')</h1>
            <p class="gate-text">@lang('Your account is verified successfully. Now you can change your password.')</p>

            <form method="POST" action="{{ route('user.password.update') }}">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="gate-field hover-input-popup">
                    <label>@lang('New Password')</label>
                    <div class="gate-input">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" class="form-control @if (gs('secure_password')) secure-password @endif" placeholder="Enter new password" required>
                    </div>
                </div>
                <div class="gate-field">
                    <label>@lang('Confirm Password')</label>
                    <div class="gate-input">
                        <i class="fas fa-shield-alt"></i>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm new password" required>
                    </div>
                </div>
                <button type="submit" class="gate-btn">@lang('Submit')</button>
            </form>
        </div>
    </section>
@endsection

@push('style')
    @include($activeTemplate . 'partials.auth_gate_style')
@endpush
