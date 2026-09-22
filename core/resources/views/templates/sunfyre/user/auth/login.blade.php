@extends($activeTemplate . 'layouts.app')

@section('app')
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

<style>
    body, html {
        margin: 0;
        padding: 0;
        background-color: #e8f0fa;
        background-image: none;
        font-family: 'Roboto', Arial, sans-serif;
        color: #172033;
        min-height: 100vh;
    }

    .mobile-container {
        max-width: 450px;
        margin: 0 auto;
        padding: 20px;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        position: relative;
    }

    /* Back Button */
    .back-btn {
        color: #123b66;
        font-size: 20px;
        text-decoration: none;
        margin-top: 10px;
        display: inline-block;
        transition: opacity 0.2s;
    }
    .back-btn:active { opacity: 0.6; }

    /* Logo and Titles */
    .logo-text {
        text-align: center;
        font-size: 34px;
        font-weight: 900;
        font-style: italic;
        color: #123b66;
        font-family: "Arial Black", sans-serif;
        letter-spacing: 1.5px;
        margin-top: 25px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.4);
    }
    .logo-text img { 
        max-width: 150px; 
        max-height: 70px; 
        object-fit: contain; 
        display: inline-block; 
    }
    .page-title {
        text-align: center;
        color: #123b66;
        font-size: 24px;
        font-weight: bold;
        margin-top: 30px;
    }
    .register-link {
        text-align: center;
        font-size: 14px;
        margin-top: 8px;
        margin-bottom: 35px;
        color: #6b7280;
    }
    .register-link a {
        color: #2563eb;
        text-decoration: underline;
        font-weight: 500;
    }

    /* Error Message Box */
    .error-box {
        background-color: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.5);
        color: #fca5a5;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
        text-align: center;
    }

    /* Form Inputs */
    .input-group {
        position: relative;
        margin-bottom: 20px;
    }
    .input-group input {
        width: 100%;
        background-color: #f8fafc;
        border: 1px solid #d5e4f7;
        border-radius: 10px;
        padding: 16px 16px 16px 48px;
        color: #172033;
        font-size: 15px;
        box-sizing: border-box;
        outline: none;
        transition: border-color 0.2s;
    }
    .input-group input::placeholder {
        color: #6b7280;
        font-weight: 500;
    }
    .input-group input:focus {
        border-color: #2563eb;
    }
    .input-group i.left-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #6b7280;
        font-size: 16px;
    }
    .input-group i.right-icon {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #6b7280;
        font-size: 16px;
        cursor: pointer;
    }

    /* Options Row (Remember / Forgot) */
    .options-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding: 0 4px;
    }
    .remember-label {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #6b7280;
        font-size: 14px;
        cursor: pointer;
        font-weight: 500;
    }
    .custom-checkbox {
        appearance: none;
        width: 20px;
        height: 20px;
        background-color: transparent;
        border: 2px solid #123b66;
        border-radius: 50%;
        position: relative;
        cursor: pointer;
        outline: none;
        flex-shrink: 0;
    }
    .custom-checkbox:checked {
        background-color: #123b66;
    }
    .custom-checkbox:checked::after {
        content: '\f00c';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        font-size: 11px;
        color: #000;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    .forgot-link {
        color: #123b66;
        font-size: 14px;
        text-decoration: none;
        font-weight: 500;
    }

    /* 3D Login Button */
    .btn-login {
        width: 100%;
        background: linear-gradient(180deg, #2563eb 0%, #123b66 100%);
        border: 1px solid rgba(255,255,255,0.4);
        border-radius: 8px;
        padding: 14px;
        font-size: 18px;
        font-weight: 900;
        color: #ffffff;
        cursor: pointer;
        box-shadow: 0 8px 20px rgba(18, 59, 102, 0.25);
        transition: transform 0.1s, box-shadow 0.1s;
    }
    .btn-login:active {
        transform: translateY(4px);
        box-shadow: 0 2px 8px rgba(18, 59, 102, 0.2);
    }

    /* Divider (Or Connect With) */
    .divider {
        display: flex;
        align-items: center;
        text-align: center;
        color: #6b7280;
        font-size: 12px;
        font-weight: bold;
        margin: 35px 0 25px;
    }
    .divider::before, .divider::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid rgba(255,255,255,0.3);
    }
    .divider::before { margin-right: 15px; }
    .divider::after { margin-left: 15px; }

    /* Social Icons */
    .social-row {
        display: flex;
        justify-content: center;
        gap: 20px;
    }
    .social-btn {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        cursor: pointer;
        text-decoration: none;
        transition: transform 0.2s;
    }
    .social-btn:active { transform: scale(0.9); }
    
    .social-btn.fb {
        background: #3b5998;
        color: #ffffff;
    }
    .social-btn.google {
        background: #ffffff;
        border: 1px solid #ddd;
    }
    .google-icon {
        width: 26px;
        height: 26px;
    }

    /* Autofill fix */
    input:-webkit-autofill,
    input:-webkit-autofill:hover, 
    input:-webkit-autofill:focus, 
    input:-webkit-autofill:active {
        -webkit-box-shadow: 0 0 0 1000px #f8fafc inset !important;
        -webkit-text-fill-color: #172033 !important;
        transition: background-color 5000s ease-in-out 0s;
    }
</style>

<div class="mobile-container">
    
    <a href="{{ route('home') }}" class="back-btn">
        <i class="fa-solid fa-chevron-left"></i>
    </a>

    <div class="auth-lang">
        @include($activeTemplate . 'partials.lang_switch')
    </div>

    <div class="logo-text">
        @if(siteLogo())
            <img src="{{ siteLogo() }}" alt="{{ __(gs('site_name')) }}">
        @else
            {{ __(gs('site_name')) }}
        @endif
    </div>

    <div class="page-title">@lang('Login')</div>
    <div class="register-link">
        @lang('No account yet?') <a href="{{ route('user.register') }}">@lang('Register')</a>
    </div>

    @if($errors->any())
        <div class="error-box">
            @foreach($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('user.login') }}">
        @csrf
        
        <div class="input-group">
            <i class="fa-solid fa-phone left-icon"></i>
            <input type="text" name="username" placeholder="@lang('Phone number')" value="{{ old('username') }}" required autocomplete="off">
        </div>

        <div class="input-group">
            <i class="fa-solid fa-lock left-icon"></i>
            <input type="password" name="password" id="password" placeholder="@lang('Password')" required>
            <i class="fa-solid fa-eye-slash right-icon" id="togglePass" onclick="togglePassword()"></i>
        </div>

        <div class="options-row">
            <label class="remember-label">
                <input type="checkbox" name="remember" class="custom-checkbox" {{ old('remember') ? 'checked' : '' }}>
                @lang('Remember')
            </label>
            <a href="{{ route('user.password.request') }}" class="forgot-link">@lang('Forgot password?')</a>
        </div>

        <button type="submit" class="btn-login">@lang('Login')</button>
    </form>

    <div class="divider">@lang('Or Connect With')</div>

    <div class="social-row">
        <a href="javascript:void(0)" class="social-btn fb">
            <i class="fa-brands fa-facebook-f"></i>
        </a>
        <a href="javascript:void(0)" class="social-btn google">
            <svg class="google-icon" viewBox="0 0 24 24">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                <path d="M1 1h22v22H1z" fill="none"/>
            </svg>
        </a>
    </div>

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
