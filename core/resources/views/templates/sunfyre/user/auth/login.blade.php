@extends($activeTemplate . 'layouts.app')

@section('app')
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

<style>
    :root {
        --auth-bg: #0b0f14;
        --auth-card: rgba(15, 20, 25, 0.84);
        --auth-teal: #2dd4a8;
        --auth-gold: #e8b84a;
        --auth-text: #e8eef5;
        --auth-muted: #8b97a8;
        --auth-input: rgba(11, 15, 20, 0.88);
    }

    body, html {
        margin: 0;
        padding: 0;
        min-height: 100%;
        background: var(--auth-bg);
        font-family: 'Segoe UI', Arial, sans-serif;
        color: var(--auth-text);
    }

    .auth-page {
        position: relative;
        min-height: 100vh;
        display: flex;
        justify-content: center;
        overflow-x: hidden;
    }

    .auth-bg {
        position: fixed;
        inset: 0;
        z-index: 0;
        background:
            linear-gradient(120deg, rgba(11,15,20,0.78) 0%, rgba(11,15,20,0.55) 48%, rgba(11,15,20,0.8) 100%),
            url('{{ asset('assets/images/frontend/auth/gaming-bg.jpg') }}') center center / cover no-repeat;
    }
    .auth-bg::after {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse at 75% 25%, rgba(45,212,168,0.14), transparent 42%),
            radial-gradient(ellipse at 15% 85%, rgba(232,184,74,0.12), transparent 40%);
        pointer-events: none;
    }

    .auth-shell {
        position: relative;
        z-index: 1;
        width: 100%;
        max-width: 1100px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1.05fr 0.95fr;
        gap: 28px;
        padding: 40px 28px;
        align-items: center;
        min-height: 100vh;
        box-sizing: border-box;
    }

    .auth-hero { padding: 12px 8px 12px 12px; }
    .auth-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 12px;
        border-radius: 999px;
        background: rgba(45,212,168,0.12);
        border: 1px solid rgba(45,212,168,0.28);
        color: var(--auth-teal);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.4px;
        text-transform: uppercase;
        margin-bottom: 16px;
    }
    .auth-hero h1 {
        margin: 0 0 12px;
        font-size: clamp(28px, 4vw, 42px);
        line-height: 1.15;
        font-weight: 800;
        color: #fff;
    }
    .auth-hero h1 span { color: var(--auth-gold); }
    .auth-hero p {
        margin: 0;
        max-width: 430px;
        color: var(--auth-muted);
        font-size: 15px;
        line-height: 1.6;
    }

    .auth-card {
        width: 100%;
        max-width: 460px;
        margin-left: auto;
        background: var(--auth-card);
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
        border: 1px solid rgba(255,255,255,0.10);
        border-radius: 20px;
        padding: 28px 24px 30px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.45);
        box-sizing: border-box;
    }

    .back-btn {
        color: var(--auth-gold);
        font-size: 15px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 8px;
        font-weight: 600;
    }
    .logo-text { text-align: center; margin: 8px 0 6px; }
    .logo-text img {
        max-width: 220px;
        max-height: 72px;
        object-fit: contain;
        display: inline-block;
    }
    .page-title {
        text-align: center;
        color: var(--auth-text);
        font-size: 24px;
        font-weight: 800;
        margin-top: 10px;
    }
    .register-link {
        text-align: center;
        font-size: 14px;
        margin-top: 8px;
        margin-bottom: 22px;
        color: var(--auth-muted);
    }
    .register-link a {
        color: var(--auth-teal);
        text-decoration: none;
        font-weight: 700;
    }

    .error-box {
        background: rgba(239, 68, 68, 0.12);
        border: 1px solid rgba(239, 68, 68, 0.4);
        color: #fca5a5;
        padding: 12px;
        border-radius: 10px;
        margin-bottom: 16px;
        font-size: 14px;
        text-align: center;
    }

    .auth-field { position: relative; margin-bottom: 16px; }
    .auth-field input {
        width: 100%;
        background: var(--auth-input);
        border: 1px solid rgba(255,255,255,0.10);
        border-radius: 12px;
        padding: 15px 16px 15px 46px;
        color: var(--auth-text);
        font-size: 15px;
        box-sizing: border-box;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        appearance: none;
    }
    .auth-field input::placeholder { color: var(--auth-muted); font-weight: 500; }
    .auth-field input:focus {
        border-color: rgba(45,212,168,0.55);
        box-shadow: 0 0 0 3px rgba(45,212,168,0.12);
    }
    .auth-field i.left-icon,
    .auth-field i.right-icon {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        color: var(--auth-teal);
        font-size: 15px;
    }
    .auth-field i.left-icon { left: 16px; }
    .auth-field i.right-icon { right: 16px; cursor: pointer; }

    .options-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        gap: 10px;
    }
    .remember-label {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--auth-text);
        font-size: 13px;
        cursor: pointer;
        font-weight: 500;
    }
    .custom-checkbox {
        appearance: none;
        width: 18px;
        height: 18px;
        border: 2px solid var(--auth-gold);
        border-radius: 50%;
        position: relative;
        cursor: pointer;
        outline: none;
        flex-shrink: 0;
        background: transparent;
    }
    .custom-checkbox:checked { background: var(--auth-gold); }
    .custom-checkbox:checked::after {
        content: '\f00c';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        font-size: 10px;
        color: #0b0f14;
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
    }
    .forgot-link {
        color: var(--auth-gold);
        font-size: 13px;
        text-decoration: none;
        font-weight: 600;
        white-space: nowrap;
    }

    .btn-login {
        width: 100%;
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, rgba(45,212,168,0.18) 0%, #151b24 55%);
        border: 1px solid rgba(45,212,168,0.35);
        border-radius: 12px;
        padding: 14px;
        font-size: 16px;
        font-weight: 800;
        color: var(--auth-text);
        cursor: pointer;
        transition: transform 0.15s;
    }
    .btn-login::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0; width: 3px;
        background: var(--auth-teal);
        border-radius: 12px 0 0 12px;
    }
    .btn-login:active { transform: scale(0.98); }

    .divider {
        display: flex;
        align-items: center;
        color: var(--auth-muted);
        font-size: 12px;
        font-weight: 700;
        margin: 26px 0 18px;
    }
    .divider::before, .divider::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid rgba(255,255,255,0.12);
    }
    .divider::before { margin-right: 12px; }
    .divider::after { margin-left: 12px; }

    .social-row { display: flex; justify-content: center; gap: 14px; }
    .social-btn {
        width: 46px; height: 46px; border-radius: 50%;
        display: inline-flex; align-items: center; justify-content: center;
        text-decoration: none; transition: transform 0.15s;
    }
    .social-btn:active { transform: scale(0.92); }
    .social-btn.google { background: #fff; border: 1px solid #ddd; }
    .google-icon { width: 24px; height: 24px; }

    input:-webkit-autofill,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:focus,
    input:-webkit-autofill:active {
        -webkit-box-shadow: 0 0 0 1000px #0f1419 inset !important;
        -webkit-text-fill-color: #e8eef5 !important;
        transition: background-color 5000s ease-in-out 0s;
    }

    @media (max-width: 900px) {
        .auth-shell {
            grid-template-columns: 1fr;
            padding: 18px 16px 28px;
            align-items: flex-start;
            gap: 0;
        }
        .auth-hero { display: none; }
        .auth-card {
            margin: 10px auto 0;
            max-width: 480px;
            padding: 22px 18px 26px;
            background: rgba(11,15,20,0.82);
        }
        .auth-bg {
            background:
                linear-gradient(180deg, rgba(11,15,20,0.5) 0%, rgba(11,15,20,0.78) 50%, rgba(11,15,20,0.92) 100%),
                url('{{ asset('assets/images/frontend/auth/gaming-bg.jpg') }}') center top / cover no-repeat;
        }
    }

    @media (max-width: 420px) {
        .logo-text img { max-width: 180px; max-height: 58px; }
        .page-title { font-size: 22px; }
        .auth-card { padding: 18px 14px 22px; border-radius: 16px; }
    }
</style>

<div class="auth-page">
    <div class="auth-bg" aria-hidden="true"></div>
    <div class="auth-shell">
        <div class="auth-hero">
            <div class="auth-hero-badge"><i class="fas fa-gamepad"></i> Gaming Zone</div>
            <h1>Welcome to <span>{{ __(gs('site_name')) }}</span></h1>
            <p>Sign in to play slots, live casino, sports and more. Fast deposit, secure account, instant access.</p>
        </div>

        <div class="auth-card">
            <a href="{{ route('home') }}" class="back-btn">
                <i class="fa-solid fa-chevron-left"></i> @lang('Back')
            </a>

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
                <div class="auth-field">
                    <i class="fa-solid fa-phone left-icon"></i>
                    <input type="text" name="username" placeholder="@lang('Phone number')" value="{{ old('username') }}" required autocomplete="off">
                </div>
                <div class="auth-field">
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

            @if (@gs('socialite_credentials')->google->status == Status::ENABLE)
            <div class="divider">@lang('Or Connect With')</div>
            <div class="social-row">
                <a href="{{ route('user.social.login', 'google') }}" class="social-btn google" title="@lang('Login with Google')">
                    <svg class="google-icon" viewBox="0 0 24 24">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                        <path d="M1 1h22v22H1z" fill="none"/>
                    </svg>
                </a>
            </div>
            @endif
        </div>
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
