@extends($activeTemplate . 'layouts.app')

@section('app')
    @php
        $banned = getContent('banned.content', true);
    @endphp
    <section class="gate-page">
        <div class="gate-card">
            <a href="{{ route('home') }}" class="gate-back"><i class="fas fa-chevron-left"></i> @lang('Back')</a>
            <div class="gate-logo"><img src="{{ siteLogo() }}" alt="{{ gs('site_name') }}"></div>
            <div class="gate-kicker">Account</div>
            <h1 class="gate-title">{{ __(@$banned->data_values->heading) }}</h1>
            <div class="gate-alert">
                @lang('Reason')<br>
                {{ __($user->ban_reason) }}
            </div>
            <a href="{{ route('home') }}" class="gate-btn">@lang('Go to Home')</a>
        </div>
    </section>
@endsection

@push('style')
    @include($activeTemplate . 'partials.auth_gate_style')
@endpush
