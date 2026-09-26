@php
    $socialLinks = gs('social_links');
    $wa = trim(@$socialLinks->whatsapp ?? '');
    $tg = trim(@$socialLinks->telegram ?? '');
@endphp
@if($wa || $tg)
<div class="float-social-btns">
    @if($wa)
    <a href="{{ $wa }}" class="float-btn wa-float" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">
        <img src="{{ asset('assets/images/social/whatsapp.svg') }}" alt="WhatsApp">
    </a>
    @endif
    @if($tg)
    <a href="{{ $tg }}" class="float-btn tg-float" target="_blank" rel="noopener noreferrer" aria-label="Telegram">
        <img src="{{ asset('assets/images/social/telegram.svg') }}" alt="Telegram">
    </a>
    @endif
</div>
@endif
