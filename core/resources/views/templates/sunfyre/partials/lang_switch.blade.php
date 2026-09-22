{{-- Compact EN / বাংলা language switcher --}}
@if (gs('multi_language'))
@php
    $activeLang = session('lang', 'bn');
@endphp
<div class="lang-switch" role="group" aria-label="Language">
    <a href="{{ route('lang', 'bn') }}" class="lang-switch__btn {{ $activeLang === 'bn' ? 'is-active' : '' }}" title="বাংলা">বাং</a>
    <a href="{{ route('lang', 'en') }}" class="lang-switch__btn {{ $activeLang === 'en' ? 'is-active' : '' }}" title="English">EN</a>
</div>
@endif
