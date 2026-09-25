@extends($activeTemplate . 'layouts.app')

@section('app')
@php
    $register = getContent('register.content', true);
    $policyPages = getContent('policy_pages.element', false, null, true);
@endphp

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
    .d-none { display: none !important; }

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
        background: rgba(232,184,74,0.12);
        border: 1px solid rgba(232,184,74,0.28);
        color: var(--auth-gold);
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
    .auth-hero h1 span { color: var(--auth-teal); }
    .auth-hero p {
        margin: 0;
        max-width: 430px;
        color: var(--auth-muted);
        font-size: 15px;
        line-height: 1.6;
    }

    .auth-card {
        width: 100%;
        max-width: 480px;
        margin-left: auto;
        background: var(--auth-card);
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
        border: 1px solid rgba(255,255,255,0.10);
        border-radius: 20px;
        padding: 26px 22px 28px;
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
        margin-bottom: 20px;
        color: var(--auth-muted);
    }
    .register-link a {
        color: var(--auth-teal);
        text-decoration: none;
        font-weight: 700;
    }

    .error-box, .disabled-box {
        background: rgba(239, 68, 68, 0.12);
        border: 1px solid rgba(239, 68, 68, 0.4);
        color: #fca5a5;
        padding: 12px;
        border-radius: 10px;
        margin-bottom: 16px;
        font-size: 14px;
        text-align: center;
    }
    .disabled-box { font-size: 16px; font-weight: 700; padding: 20px; }

    .auth-field { position: relative; margin-bottom: 14px; }
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
        -moz-appearance: textfield;
        appearance: none;
    }
    .auth-field input::-webkit-outer-spin-button,
    .auth-field input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .auth-field input::placeholder { color: var(--auth-muted); font-weight: 500; }
    .auth-field input:focus {
        border-color: rgba(232,184,74,0.55);
        box-shadow: 0 0 0 3px rgba(232,184,74,0.12);
    }
    .auth-field i.left-icon,
    .auth-field i.right-icon {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        color: var(--auth-gold);
        font-size: 15px;
    }
    .auth-field i.left-icon { left: 16px; }
    .auth-field i.right-icon { right: 16px; cursor: pointer; }
    .auth-field .error-text {
        color: #fca5a5;
        font-size: 11px;
        margin-top: 4px;
        display: block;
    }

    .checkbox-container {
        display: flex;
        align-items: flex-start;
        cursor: pointer;
        position: relative;
        padding-left: 28px;
        color: var(--auth-muted);
        font-size: 13px;
        margin-bottom: 0;
        user-select: none;
    }
    .checkbox-container input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }
    .checkmark {
        position: absolute;
        left: 0;
        top: 2px;
        height: 18px;
        width: 18px;
        border: 2px solid var(--auth-gold);
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        flex-shrink: 0;
    }
    .checkbox-container input:checked ~ .checkmark { background: var(--auth-gold); }
    .checkmark i { display: none; color: #0b0f14; font-size: 11px; }
    .checkbox-container input:checked ~ .checkmark i { display: block; }
    .agree-text { color: var(--auth-muted); font-size: 12px; line-height: 1.4; }
    .agree-text a { color: var(--auth-gold); text-decoration: none; }

    .btn-register {
        width: 100%;
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, rgba(232,184,74,0.18) 0%, #151b24 55%);
        border: 1px solid rgba(232,184,74,0.35);
        border-radius: 12px;
        padding: 14px;
        font-size: 16px;
        font-weight: 800;
        color: var(--auth-text);
        cursor: pointer;
        transition: transform 0.15s;
    }
    .btn-register::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0; width: 3px;
        background: var(--auth-gold);
        border-radius: 12px 0 0 12px;
    }
    .btn-register:active { transform: scale(0.98); }

    .divider {
        display: flex;
        align-items: center;
        color: var(--auth-muted);
        font-size: 12px;
        font-weight: 700;
        margin: 24px 0 16px;
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
    .captcha-wrapper { margin-bottom: 14px; }

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
            <div class="auth-hero-badge"><i class="fas fa-user-plus"></i> Create Account</div>
            <h1>Join <span>{{ __(gs('site_name')) }}</span></h1>
            <p>Register once and unlock slots, live casino, sports betting and exclusive rewards.</p>
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

            <div class="page-title">@lang('Register')</div>
            <div class="register-link">
                @lang('Already have an account?') <a href="{{ route('user.login') }}">@lang('Login')</a>
            </div>

            @if(!gs('registration'))
                <div class="disabled-box">
                    <i class="fas fa-lock" style="display:block; font-size:32px; margin-bottom:12px;"></i>
                    @lang('Registration is disabled currently')
                </div>
            @else
                @if($errors->any())
                    <div class="error-box">
                        @foreach($errors->all() as $error)
                            {{ $error }}<br>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('user.register') }}" method="POST" id="registrationForm">
                    @csrf

                    @if(session()->get('reference') != null)
                    <div class="auth-field">
                        <i class="fa-solid fa-user-tag left-icon"></i>
                        <input type="text" name="referBy" value="{{ session()->get('reference') }}" readonly placeholder="@lang('Reference By')">
                    </div>
                    @endif

                    <input type="hidden" name="lastname" value="★">
                    <input type="hidden" name="username" class="checkUser" value="{{ old('username') }}">
                    <input type="hidden" name="email" class="checkUser" value="{{ old('email') }}">
                    <input type="hidden" name="country" value="Bangladesh">
                    <input type="hidden" name="mobile_code" value="880">
                    <input type="hidden" name="country_code" value="BD">
                    <input type="hidden" name="password_confirmation" id="passwordConfirmInput">

                    <div class="auth-field">
                        <i class="fa-solid fa-user left-icon"></i>
                        <input type="text" name="firstname" value="{{ old('firstname') }}" required autocomplete="off" placeholder="@lang('Full Name')">
                    </div>

                    <div class="auth-field">
                        <i class="fa-solid fa-phone left-icon"></i>
                        <input type="tel" name="mobile" id="mobileInput" value="{{ old('mobile') }}" class="checkUser" placeholder="@lang('Mobile Number')" required autocomplete="tel" inputmode="numeric" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);">
                        <span class="error-text" id="mobileError"></span>
                        <span class="error-text mobileExist"></span>
                    </div>

                    <div class="auth-field">
                        <i class="fa-solid fa-lock left-icon"></i>
                        <input type="password" name="password" id="passwordInput" placeholder="@lang('Password')" required>
                        <i class="fa-solid fa-eye-slash right-icon toggle-password" id="togglePass" onclick="togglePassword()"></i>
                    </div>

                    <div class="captcha-wrapper">
                        <x-captcha />
                    </div>

                    @if(gs('agree'))
                    <div class="auth-field" style="margin-bottom: 18px;">
                        <label class="checkbox-container">
                            <input type="checkbox" name="agree" id="agree" @checked(old('agree')) required>
                            <span class="checkmark"><i class="fas fa-check"></i></span>
                            <span class="agree-text">
                                @lang('I agree with')
                                @foreach ($policyPages as $policy)
                                    <a href="{{ route('policy.pages', $policy->slug) }}">{{ __($policy->data_values->title) }}</a>
                                    @if(!$loop->last), @endif
                                @endforeach
                            </span>
                        </label>
                    </div>
                    @endif

                    <button type="submit" class="btn-register" id="regSubmitBtn">@lang('Register')</button>
                </form>

                @if (@gs('socialite_credentials')->google->status == Status::ENABLE)
                <div class="divider">@lang('Or Connect With')</div>
                <div class="social-row">
                    <a href="{{ route('user.social.login', 'google') }}" class="social-btn google" title="@lang('Register with Google')">
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
            @endif
        </div>
    </div>
</div>

<div id="existModal" style="position:fixed; inset:0; z-index:100; background:rgba(0,0,0,0.6); display:none; align-items:center; justify-content:center; padding:16px;">
    <div style="background:#0f1419; border:1px solid #e8b84a; border-radius:12px; padding:24px; max-width:360px; width:100%; text-align:center;">
        <h5 style="color:#e8b84a; font-size:16px; font-weight:700; margin-bottom:12px;">@lang('You are with us')</h5>
        <p style="color:#8b97a8; font-size:14px; margin-bottom:20px;">@lang('You already have an account please Login')</p>
        <div style="display:flex; gap:10px;">
            <button onclick="closeExistModal()" style="flex:1; background:#151b24; color:#fff; border:1px solid rgba(255,255,255,0.1); padding:10px; border-radius:8px; font-weight:700; cursor:pointer;">@lang('Close')</button>
            <a href="{{ route('user.login') }}" style="flex:1; background:linear-gradient(135deg,rgba(45,212,168,0.2),#151b24); color:#e8eef5; border:1px solid rgba(45,212,168,0.35); padding:10px; border-radius:8px; font-weight:700; text-decoration:none; text-align:center;">@lang('Login')</a>
        </div>
    </div>
</div>

@if(gs('secure_password'))
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush
@endif

<script>
    function togglePassword() {
        const input = document.getElementById('passwordInput');
        const icon = document.getElementById('togglePass');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    }

    function closeExistModal() {
        document.getElementById('existModal').style.display = 'none';
    }

    document.addEventListener('DOMContentLoaded', function() {
        var mobileInput = document.getElementById('mobileInput');
        if (!mobileInput) return;

        mobileInput.addEventListener('input', function() {
            var mobile = this.value;
            if (mobile.length > 11) {
                mobile = mobile.slice(0, 11);
                this.value = mobile;
            }
            document.querySelector('input[name=username]').value = mobile;
            document.querySelector('input[name=email]').value = mobile + '@gmail.com';
            validateMobile(mobile);
        });

        function validateMobile(number) {
            var errorSpan = document.getElementById('mobileError');
            if (number.length > 0 && !number.startsWith('0')) {
                errorSpan.textContent = 'Mobile number must start with 0';
                return false;
            } else if (number.length > 0 && number.length !== 11) {
                errorSpan.textContent = 'Mobile number must be exactly 11 digits';
                return false;
            } else {
                errorSpan.textContent = '';
                return true;
            }
        }

        document.getElementById('passwordInput').addEventListener('input', function() {
            document.getElementById('passwordConfirmInput').value = this.value;
        });

        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            var mobile = mobileInput.value;
            if (!mobile.startsWith('0') || mobile.length !== 11) {
                e.preventDefault();
                document.getElementById('mobileError').textContent = 'Invalid Mobile! Must start with 0 and be 11 digits.';
                mobileInput.focus();
                return false;
            }
        });
    });
</script>
@endsection
