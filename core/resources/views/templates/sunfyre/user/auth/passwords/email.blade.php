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
    .mobile-container .input-group {
        position: relative;
        display: block;
        width: 100%;
        margin-bottom: 18px;
    }
    .mobile-container .input-group input {
        width: 100%;
        box-sizing: border-box;
        height: 52px;
        padding: 0 16px 0 46px;
        background: #f8fafc;
        border: 1.5px solid #d5e4f7;
        border-radius: 14px;
        color: #172033;
        font-size: 15px;
        outline: none;
    }
    .mobile-container .input-group i.left-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        z-index: 3;
        color: #6b7280;
        font-size: 16px;
        pointer-events: none;
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

    <div class="page-title">@lang('Recover Account')</div>
    <p class="auth-hint">@lang('Provide your email or username to find your account.')</p>

    @if($errors->any())
        <div class="error-box" style="background:#fef2f2;border:1px solid #fecaca;color:#b91c1c;padding:12px;border-radius:10px;margin-bottom:16px;font-size:14px;text-align:center;">
            @foreach($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('user.password.email') }}" class="verify-gcaptcha">
        @csrf
        <div class="input-group">
            <i class="fa-solid fa-user left-icon"></i>
            <input type="text" name="value" placeholder="@lang('Email or Username')" value="{{ old('value') }}" required autocomplete="off" autofocus>
        </div>

        <div class="mt-2 mb-3">
            <x-captcha />
        </div>

        <button type="submit" class="btn-login">@lang('Submit')</button>
    </form>

    <div class="register-link" style="margin-top:24px;">
        @lang('Remember your password?') <a href="{{ route('user.login') }}">@lang('Login')</a>
    </div>
</div>
@endsection
