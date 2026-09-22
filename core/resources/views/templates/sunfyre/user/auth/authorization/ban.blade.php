@extends($activeTemplate . 'layouts.app')

@section('app')
    @php
        $banned = getContent('banned.content', true);
    @endphp

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
                <h2 class="page-title text--danger">{{ __(@$banned->data_values->heading) }}</h2>
                
                <div class="banned-content mt-4">
                    <img src="{{ getImage('assets/images/frontend/banned/' . @$banned->data_values->image, '360x370') }}" alt="@lang('image')" class="img-fluid mx-auto mb-4" style="max-width: 200px;">
                    
                    <p class="register-text text-white">
                        @lang('Reason'): <br>
                        <span class="text--danger">{{ __($user->ban_reason) }}</span>
                    </p>

                    <div class="mt-4">
                        <a href="{{ route('home') }}" class="submit-btn text-decoration-none d-block">@lang('Go to Home')</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('style')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&family=Russo+One&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-dark: #e8f0fa; 
            --input-bg: #ffffff;
            --border-color: #d5e4f7;
            --primary-gold: #123b66; 
            --text-white: #ffffff;
            --icon-color: #2563eb;
        }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-dark);
        }

        .login-section {
            min-height: 100vh;
            background-color: var(--bg-dark);
            background-image: none;
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
            padding-top: 10vh;
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
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .text--danger {
            color: #ff4d4d !important;
        }

        .register-text {
            color: #ccc;
            font-size: 14px;
            line-height: 1.6;
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
            text-align: center;
        }

        .submit-btn:hover {
            background: #e6c200;
            color: #000;
        }
    </style>
@endpush