@extends($activeTemplate . 'layouts.app')

@section('app')
    @php
        $register = getContent('register.content', true);
        $policyPages = getContent('policy_pages.element', false, null, true);
    @endphp

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

    .d-none { display: none !important; }

    .mobile-container {
        max-width: 450px;
        margin: 0 auto;
        padding: 20px;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .back-btn {
        color: #123b66;
        font-size: 20px;
        text-decoration: none;
        margin-top: 10px;
        display: inline-block;
        transition: opacity 0.2s;
    }
    .back-btn:active { opacity: 0.6; }

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
        color: #ffffff;
    }
    .register-link a {
        color: #2563eb;
        text-decoration: underline;
        font-weight: 500;
    }

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
    .disabled-box {
        background-color: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.5);
        color: #fca5a5;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 16px;
        text-align: center;
        font-weight: 700;
    }

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
        color: #2563eb;
        font-size: 16px;
    }
    .input-group i.right-icon {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #2563eb;
        font-size: 16px;
        cursor: pointer;
    }
    .input-group .error-text {
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
        color: #ddd;
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
        border: 2px solid #123b66;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        flex-shrink: 0;
    }
    .checkbox-container input:checked ~ .checkmark {
        background: #123b66;
    }
    .checkmark i {
        display: none;
        color: #000;
        font-size: 11px;
    }
    .checkbox-container input:checked ~ .checkmark i {
        display: block;
    }
    .agree-text {
        color: #ddd;
        font-size: 12px;
        line-height: 1.4;
    }
    .agree-text a {
        color: #123b66;
        text-decoration: none;
    }

    .btn-register {
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
    .btn-register:active {
        transform: translateY(4px);
        box-shadow: 0 2px 8px rgba(18, 59, 102, 0.2);
    }

    .divider {
        display: flex;
        align-items: center;
        text-align: center;
        color: #ffffff;
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

    /* Captcha styling */
    .captcha-wrapper {
        margin-bottom: 20px;
    }

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
            <div class="input-group">
                <i class="fa-solid fa-user-tag left-icon"></i>
                <input type="text" name="referBy" value="{{ session()->get('reference') }}" readonly placeholder="@lang('Reference By')">
            </div>
            @endif

            {{-- Hidden fields --}}
            <input type="hidden" name="lastname" value="â˜…">
            <input type="hidden" name="username" class="checkUser" value="{{ old('username') }}">
            <input type="hidden" name="email" class="checkUser" value="{{ old('email') }}">
            <input type="hidden" name="country" value="Bangladesh">
            <input type="hidden" name="mobile_code" value="880">
            <input type="hidden" name="country_code" value="BD">
            <input type="hidden" name="password_confirmation" id="passwordConfirmInput">

            <div class="input-group">
                <i class="fa-solid fa-user left-icon"></i>
                <input type="text" name="firstname" value="{{ old('firstname') }}" required autocomplete="off" placeholder="@lang('Full Name')">
            </div>

            <div class="input-group">
                <i class="fa-solid fa-phone left-icon"></i>
                <input type="number" name="mobile" id="mobileInput" value="{{ old('mobile') }}" class="checkUser" placeholder="@lang('Mobile Number')" required autocomplete="off" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);">
                <span class="error-text" id="mobileError"></span>
                <span class="error-text mobileExist"></span>
            </div>

            <div class="input-group">
                <i class="fa-solid fa-lock left-icon"></i>
                <input type="password" name="password" id="passwordInput" placeholder="@lang('6 Digit password')" required>
                <i class="fa-solid fa-eye-slash right-icon toggle-password" id="togglePass" onclick="togglePassword()"></i>
            </div>

            <div class="captcha-wrapper">
                <x-captcha />
            </div>

            @if(gs('agree'))
            <div class="input-group" style="margin-bottom: 25px;">
                <label class="checkbox-container">
                    <input type="checkbox" name="agree" id="agree" @checked(old('agree')) required>
                    <span class="checkmark">
                        <i class="fas fa-check"></i>
                    </span>
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
    @endif
</div>

{{-- Exist Modal --}}
<div id="existModal" style="position:fixed; inset:0; z-index:100; background:rgba(0,0,0,0.6); display:none; align-items:center; justify-content:center; padding:16px;">
    <div style="background:#ffffff; border:1px solid #123b66; border-radius:12px; padding:24px; max-width:360px; width:100%; text-align:center;">
        <h5 style="color:#123b66; font-size:16px; font-weight:700; margin-bottom:12px;">@lang('You are with us')</h5>
        <p style="color:#ccc; font-size:14px; margin-bottom:20px;">@lang('You already have an account please Login')</p>
        <div style="display:flex; gap:10px;">
            <button onclick="closeExistModal()" style="flex:1; background:#d5e4f7; color:#fff; border:none; padding:10px; border-radius:6px; font-weight:700; cursor:pointer;">@lang('Close')</button>
            <a href="{{ route('user.login') }}" style="flex:1; background:#123b66; color:#000; border:none; padding:10px; border-radius:6px; font-weight:700; text-decoration:none; text-align:center;">@lang('Login')</a>
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
        
        mobileInput.addEventListener('input', function() {
            var mobile = this.value;
            
            if(mobile.length > 11) {
                mobile = mobile.slice(0, 11);
                this.value = mobile;
            }

            document.querySelector('input[name=username]').value = mobile;
            document.querySelector('input[name=email]').value = mobile + '@gmail.com';
            validateMobile(mobile);
        });

        function validateMobile(number) {
            var errorSpan = document.getElementById('mobileError');
            if(number.length > 0 && !number.startsWith('0')) {
                errorSpan.textContent = 'Mobile number must start with 0';
                return false;
            } else if(number.length > 0 && number.length !== 11) {
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
            if(!mobile.startsWith('0') || mobile.length !== 11) {
                e.preventDefault();
                document.getElementById('mobileError').textContent = 'Invalid Mobile! Must start with 0 and be 11 digits.';
                mobileInput.focus();
                return false;
            }
        });
    });
</script>

@endsection
