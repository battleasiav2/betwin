@extends('admin.layouts.master')
@section('content')
<div class="admin-login-container">
    <div class="login-glass-card">
        <div class="login-header">
            <div class="logo-wrapper">
                <h1 class="unique-logo-text">{{ __(gs('site_name')) }}</h1>
            </div>
            <p class="login-subtitle">{{ __($pageTitle) }} @lang('to Access Dashboard')</p>
        </div>

        <form action="{{ route('admin.login') }}" method="POST" class="login-form verify-gcaptcha">
            @csrf
            <div class="input-group">
                <input type="text" name="username" id="username" value="{{ old('username') }}" placeholder=" " required>
                <label for="username">@lang('Username')</label>
                <span class="input-icon">
                    <svg viewBox="0 0 24 24">
                        <path fill="currentColor" d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z" />
                    </svg>
                </span>
            </div>

            <div class="input-group">
                <input type="password" name="password" id="password" placeholder=" " required>
                <label for="password">@lang('Password')</label>
                <span class="input-icon">
                    <svg viewBox="0 0 24 24">
                        <path fill="currentColor" d="M12,17A2,2 0 0,0 14,15C14,13.89 13.1,13 12,13A2,2 0 0,0 10,15A2,2 0 0,0 12,17M18,8A2,2 0 0,1 20,10V20A2,2 0 0,1 18,22H6A2,2 0 0,1 4,20V10C4,8.89 4.9,8 6,8H7V6A5,5 0 0,1 12,1A5,5 0 0,1 17,6V8H18M12,3A3,3 0 0,0 9,6V8H15V6A3,3 0 0,0 12,3Z" />
                    </svg>
                </span>
            </div>

            <div class="captcha-area">
                <x-captcha />
            </div>

            <button type="submit" class="login-button">
                <span class="btn-text">@lang('LOGIN NOW')</span>
                <svg viewBox="0 0 24 24">
                    <path fill="currentColor" d="M4,11V13H16L10.5,18.5L11.92,19.92L19.84,12L11.92,4.08L10.5,5.5L16,11H4Z" />
                </svg>
            </button>
        </form>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Pacifico&family=Poppins:wght@400;500;600;700&display=swap');

    .admin-login-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        background-color: #f4f7f6;
        padding: 20px;
        font-family: 'Poppins', sans-serif;
    }

    .login-glass-card {
        width: 100%;
        max-width: 420px;
        background: #ffffff;
        border-radius: 24px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
        padding: 45px;
        border: 1px solid #ebebeb;
    }

    .login-header {
        text-align: center;
        margin-bottom: 35px;
    }

    .unique-logo-text {
        font-family: 'Pacifico', cursive;
        font-size: 42px;
        color: #FFD700;
        text-shadow: 1px 2px 3px rgba(0,0,0,0.05);
        margin: 0;
        display: inline-block;
    }

    .login-subtitle {
        color: #000000;
        font-size: 14px;
        font-weight: 500;
        margin-top: 10px;
        opacity: 0.8;
    }

    .login-form {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .input-group {
        position: relative;
    }

    .input-group input {
        width: 100%;
        padding: 16px 20px 16px 52px;
        border: 1.5px solid #e0e0e0;
        border-radius: 12px;
        font-size: 15px;
        transition: all 0.3s ease;
        background-color: #fafafa;
        color: #000000;
    }

    .input-group input:focus {
        outline: none;
        border-color: #FFD700;
        background-color: #ffffff;
        box-shadow: 0 0 0 4px rgba(255, 215, 0, 0.1);
    }

    .input-group label {
        position: absolute;
        left: 52px;
        top: 16px;
        color: #000000;
        font-size: 14px;
        transition: all 0.3s ease;
        pointer-events: none;
        opacity: 0.6;
    }

    .input-group input:focus + label,
    .input-group input:not(:placeholder-shown) + label {
        transform: translate(-36px, -28px) scale(0.85);
        color: #000000;
        font-weight: 600;
        background: #ffffff;
        padding: 0 6px;
        opacity: 1;
    }

    .input-icon {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: #000000;
        opacity: 0.5;
    }

    .input-icon svg {
        width: 22px;
        height: 22px;
    }

    .login-button {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        width: 100%;
        padding: 16px;
        background-color: #1a1a1a;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-text {
        color: #ffffff !important;
        font-size: 15px;
        font-weight: 600;
        letter-spacing: 0.8px;
    }

    .login-button svg {
        width: 20px;
        height: 20px;
        color: #ffffff;
        transition: transform 0.3s ease;
    }

    .login-button:hover {
        background-color: #000000;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    .login-button:hover svg {
        transform: translateX(5px);
    }

    .captcha-area {
        color: #000000;
    }
</style>
@endsection