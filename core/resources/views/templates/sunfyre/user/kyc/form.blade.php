@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="login-section">
        <div class="login-header">
            <a href="{{ route('home') }}" class="back-btn">
                <i class="fas fa-chevron-left"></i>
            </a>
        </div>

        <div class="container">
            <div class="login-wrapper" style="max-width: 500px;">
                <h2 class="page-title">KYC Verification</h2>
                <p class="register-text">
                    @lang('Please provide the required information for identity verification.')
                </p>

                <form action="{{ route('user.kyc.submit') }}" method="post" enctype="multipart/form-data" class="login-form">
                    @csrf

                    <div class="row gy-3 text-start viser-form-data">
                        <x-viser-form identifier="act" identifierValue="kyc" />
                    </div>

                    <div class="mt-4">
                        <button class="submit-btn w-100" type="submit">@lang('Submit')</button>
                    </div>
                </form>
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
            --icon-color: #00d094;
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
            margin-bottom: 5px;
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
            padding-top: 4vh; 
        }

        .login-wrapper {
            width: 100%;
            text-align: center;
        }

        .page-title {
            color: var(--primary-gold);
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .register-text {
            color: #ccc;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .viser-form-data label {
            color: #fff !important;
            font-size: 13px;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .viser-form-data input, 
        .viser-form-data select, 
        .viser-form-data textarea {
            width: 100%;
            background-color: var(--input-bg) !important;
            border: 1px solid var(--border-color) !important;
            border-radius: 8px !important;
            color: #fff !important;
            padding: 10px 15px !important;
            font-size: 14px !important;
            outline: none !important;
            appearance: none;
            -webkit-appearance: none;
        }

        .viser-form-data select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23FFD700' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 12px;
        }

        .viser-form-data select option {
            background-color: #003b36 !important;
            color: #fff !important;
        }

        .viser-form-data input[type="file"] {
            padding: 5px !important;
            cursor: pointer;
        }

        .viser-form-data input[type="file"]::file-selector-button {
            background: linear-gradient(135deg, #FFD700 0%, #B8860B 100%);
            color: #000;
            border: none;
            padding: 5px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            font-size: 12px;
            margin-right: 10px;
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

        input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 1000px var(--input-bg) inset !important;
            -webkit-text-fill-color: #fff !important;
        }
    </style>
@endpush