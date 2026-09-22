@extends($activeTemplate . 'layouts.app')

@section('app')
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

<style>
    .btn-login {
        width: 100%;
        background: linear-gradient(180deg, #2563eb 0%, #123b66 100%);
        border: none;
        border-radius: 12px;
        padding: 14px;
        font-size: 16px;
        font-weight: 700;
        color: #ffffff;
        cursor: pointer;
        box-shadow: 0 8px 20px rgba(18, 59, 102, 0.25);
    }
    .btn-login:active { transform: translateY(1px); opacity: 0.95; }
    .auth-hint {
        text-align: center;
        font-size: 14px;
        color: #6b7280;
        margin: 8px 0 28px;
        line-height: 1.45;
    }
    .logo-text {
        text-align: center;
        margin-top: 12px;
    }
    .logo-text img {
        max-width: 150px;
        max-height: 70px;
        object-fit: contain;
        display: inline-block;
    }
</style>

<div class="mobile-container">
    <a href="{{ route('user.login') }}" class="back-btn">
        <i class="fa-solid fa-chevron-left"></i>
    </a>

    <div class="logo-text">
        @if(siteLogo())
            <img src="{{ siteLogo() }}" alt="{{ __(gs('site_name')) }}">
        @else
            {{ __(gs('site_name')) }}
        @endif
    </div>

    <div class="page-title">@lang('Reset Password')</div>
    <p class="auth-hint">@lang('Your account is verified successfully. Now you can change your password.')</p>

    @if($errors->any())
        <div style="background:#fef2f2;border:1px solid #fecaca;color:#b91c1c;padding:12px;border-radius:10px;margin-bottom:16px;font-size:14px;text-align:center;">
            @foreach($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('user.password.update') }}">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="input-group hover-input-popup">
            <i class="fa-solid fa-lock left-icon"></i>
            <input type="password" name="password" id="password" class="@if (gs('secure_password')) secure-password @endif" placeholder="@lang('New Password')" required>
            <i class="fa-solid fa-eye-slash right-icon" id="togglePass" onclick="togglePassword()"></i>
        </div>

        <div class="input-group">
            <i class="fa-solid fa-shield-halved left-icon"></i>
            <input type="password" name="password_confirmation" placeholder="@lang('Confirm Password')" required>
        </div>

        <button type="submit" class="btn-login">@lang('Submit')</button>
    </form>
</div>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const icon = document.getElementById('togglePass');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    }
</script>
@endsection

@if (gs('secure_password'))
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush
@endif
