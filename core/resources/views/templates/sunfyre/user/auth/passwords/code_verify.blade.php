@extends($activeTemplate . 'layouts.app')

@section('app')
    <section class="login-section">
        <div class="login-header">
            <a href="{{ route('home') }}" class="back-btn">
                <i class="fas fa-chevron-left"></i>
            </a>
        </div>

        <div class="container">
            <div class="login-wrapper">
                <div class="site-logo-wrap mb-3">
                    <img src="{{ siteLogo() }}" alt="Logo" style="max-height: 60px;">
                </div>
                <h2 class="page-title">Verify Code</h2>
                
                <div class="verification-area">
                    <form action="{{ route('user.password.verify.code') }}" method="POST" class="submit-form login-form">
                        @csrf
                        <p class="register-text">
                            @lang('A 6 digit verification code sent to your email address') : <br>
                            <span class="text-white">{{ showEmailAddress($email) }}</span>
                        </p>
                        <input type="hidden" name="email" value="{{ $email }}">

                        <div class="mb-4">
                            @include($activeTemplate . 'partials.verification_code')
                        </div>

                        <div class="form-group">
                            <button type="submit" class="submit-btn w-100">@lang('Submit')</button>
                        </div>

                        <div class="register-text mt-4">
                            @lang('Please check including your Junk/Spam Folder. If not found, you can')
                            <a href="{{ route('user.password.request') }}">@lang('Try to send again')</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('style')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&family=Russo+One&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-dark: #002e2a; 
            --input-bg: #003b36;
            --border-color: #004d40;
            --primary-gold: #FFD700; 
            --text-white: #ffffff;
            --icon-color: #2dd4a8;
        }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-dark);
        }

        .login-section {
            min-height: 100vh;
            background-color: var(--bg-dark);
            background-image: url('{{ asset('assets/images/login_bg.jpg') }}');
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            position: relative;
            padding: 15px;
        }

        .login-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 46, 42, 0.95);
            z-index: 0;
        }

        .login-header {
            position: relative;
            z-index: 10;
            margin-bottom: 10px;
        }

        .back-btn {
            color: var(--primary-gold);
            font-size: 18px; 
            text-decoration: none;
            padding: 5px;
            display: inline-block;
        }

        .container {
            position: relative;
            z-index: 2;
            flex: 1;
            display: flex;
            align-items: flex-start; 
            justify-content: center;
            padding-top: 8vh; 
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px; 
            text-align: center;
        }

        .site-logo-wrap {
            margin-bottom: 15px;
        }

        .page-title {
            color: var(--primary-gold);
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .register-text {
            color: #ccc;
            font-size: 13px;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .register-text a {
            color: var(--icon-color);
            text-decoration: none;
            font-weight: 600;
        }

        .text-white { color: #fff !important; }

        .verification-code span {
            background-color: var(--input-bg) !important;
            border: 1px solid var(--border-color) !important;
            color: var(--primary-gold) !important;
            border-radius: 8px !important;
            width: 45px !important;
            height: 50px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 20px !important;
            font-weight: 700 !important;
        }

        .verification-code::after {
            display: none !important;
        }

        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 1000px var(--input-bg) inset !important;
            -webkit-text-fill-color: #fff !important;
            transition: background-color 5000s ease-in-out 0s;
        }

        .submit-btn {
            width: 100%;
            background: var(--primary-gold);
            color: #000;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            transition: background 0.3s;
        }

        .submit-btn:hover {
            background: #e6c200;
        }
    </style>
@endpush