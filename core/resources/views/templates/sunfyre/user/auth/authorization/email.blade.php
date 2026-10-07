@extends($activeTemplate . 'layouts.app')

@section('app')
    <section class="gate-page">
        <div class="gate-card">
            <a href="{{ route('home') }}" class="gate-back"><i class="fas fa-chevron-left"></i> @lang('Back')</a>
            <div class="gate-logo"><img src="{{ siteLogo() }}" alt="{{ gs('site_name') }}"></div>
            <div class="gate-kicker">Verify</div>
            <h1 class="gate-title">@lang('Email Verification')</h1>
            <form action="{{ route('user.verify.email') }}" method="POST" class="submit-form">
                @csrf
                <p class="gate-text">
                    @lang('A 6 digit verification code sent to your email address')<br>
                    <span class="text-white">{{ showEmailAddress(auth()->user()->email) }}</span>
                </p>
                @include($activeTemplate . 'partials.verification_code')
                <button type="submit" class="gate-btn">@lang('Submit')</button>
                <p class="gate-note">
                    @lang('If you don\'t get any code'),
                    <span class="countdown-wrapper">@lang('try again after') <span id="countdown" class="fw-bold">--</span> @lang('seconds')</span>
                    <a href="{{ route('user.send.verify.code', 'email') }}" class="try-again-link d-none">@lang('Try again')</a>
                </p>
            </form>
        </div>
    </section>
@endsection

@push('style')
    @include($activeTemplate . 'partials.auth_gate_style')
@endpush

@push('script')
    <script>
        var distance = Number("{{ @$user->ver_code_send_at->addMinutes(2)->timestamp - time() }}");
        var x = setInterval(function() {
            distance--;
            document.getElementById("countdown").innerHTML = distance;
            if (distance <= 0) {
                clearInterval(x);
                document.querySelector('.countdown-wrapper').classList.add('d-none');
                document.querySelector('.try-again-link').classList.remove('d-none');
            }
        }, 1000);
    </script>
@endpush
