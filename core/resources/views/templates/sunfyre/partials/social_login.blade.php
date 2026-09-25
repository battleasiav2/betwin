@php
    $text = isset($register) ? 'Register' : 'Login';
@endphp
@if (@gs('socialite_credentials')->google->status == Status::ENABLE)
    <h3 class="account-form__other">
        <span class="account-form__other-line"></span>
        <span>@lang('or')</span>
    </h3>
    <ul class="social-list">
        <li class="social-list__item">
            <a class="social-list__link flex-center google" href="{{ route('user.social.login', 'google') }}">
                <i class="fab fa-google"></i>
            </a>
        </li>
    </ul>
@endif

@push('style')
    <style>
        .social-login-btn {
            border: 1px solid #cbc4c4;
        }
    </style>
@endpush
