@extends($activeTemplate . 'layouts.app')

@section('app')
    <section class="login-section">
        <div class="login-header">
            <a href="{{ route('user.login') }}" class="back-btn">
                <i class="fas fa-chevron-left"></i>
            </a>
        </div>

        <div class="container">
            <div class="login-wrapper">
                <div class="site-logo-wrap mb-3">
                    <img src="{{ siteLogo() }}" alt="Logo" style="max-height: 60px;">
                </div>
                <h2 class="page-title">Reset Password</h2>
                
                <p class="register-text">
                    @lang('Your account is verified successfully. Now you can change your password.')
                </p>

                <form method="POST" action="{{ route('user.password.update') }}" class="login-form">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="form-group hover-input-popup">
                        <label class="input-label">@lang('New Password')</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" name="password" class="form-control @if (gs('secure_password')) secure-password @endif" placeholder="Enter new password" required>
                            <span class="password-toggle toggle-password">
                                <i class="fas fa-eye-slash"></i>
                            </span>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <label class="input-label">@lang('Confirm Password')</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <i class="fas fa-shield-alt"></i>
                            </span>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm new password" required>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="submit-btn">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@if (gs('secure_password'))
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush
@endif

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
            padding-top: 10vh;
        }

        .login-wrapper {
            width: 100%;
            max-width: 380px; 
            text-align: center;
        }

        .site-logo-wrap {
            margin-bottom: 15px;
        }

        .page-title {
            color: var(--primary-gold);
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .register-text {
            color: #ccc;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .form-group {
            text-align: left;
            margin-bottom: 15px;
        }

        .input-label {
            color: #b0b0b0;
            font-size: 13px;
            margin-bottom: 5px;
            display: block;
            padding-left: 2px;
        }

        .input-wrapper {
            position: relative;
            height: 45px; 
        }

        .form-control {
            width: 100%;
            height: 100%;
            background-color: var(--input-bg) !important;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: #fff !important;
            padding-left: 40px; 
            padding-right: 40px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            border-color: var(--icon-color);
            background-color: var(--input-bg) !important;
            box-shadow: none;
        }

        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 1000px var(--input-bg) inset !important;
            -webkit-text-fill-color: #fff !important;
            transition: background-color 5000s ease-in-out 0s;
        }

        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--icon-color);
            font-size: 15px;
            pointer-events: none;
            z-index: 5;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
            cursor: pointer;
            font-size: 14px;
            z-index: 10;
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

        .hover-input-popup .input-popup {
            bottom: 100% !important;
            background-color: #003b36 !important;
            color: #fff !important;
            border: 1px solid var(--border-color) !important;
            padding: 10px !important;
            border-radius: 8px !important;
        }
    </style>
@endpush

@push('script')
    <script>
        $('.toggle-password').on('click', function() {
            let input = $(this).siblings('input');
            let icon = $(this).find('i');
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            } else {
                input.attr('type', 'password');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            }
        });
    </script>
@endpush