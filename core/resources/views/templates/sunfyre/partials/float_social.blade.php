@php
    $socialLinks = gs('social_links');
    $wa = trim(@$socialLinks->whatsapp ?? '');
    $tg = trim(@$socialLinks->telegram ?? '');
    $fb = trim(@$socialLinks->facebook ?? '');
    $support = trim(@$socialLinks->support ?? '');
@endphp
@if($wa || $fb || $tg || $support)
<div class="float-social-btns">
    @if($wa)
    <a href="{{ $wa }}" class="float-btn wa-float" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">
        <img src="{{ asset('assets/images/social/whatsapp.svg') }}" alt="WhatsApp">
    </a>
    @endif
    @if($fb)
    <a href="{{ $fb }}" class="float-btn fb-float" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
        <img src="{{ asset('assets/images/social/facebook.svg') }}" alt="Facebook">
    </a>
    @endif
    @if($tg)
    <a href="{{ $tg }}" class="float-btn tg-float" target="_blank" rel="noopener noreferrer" aria-label="Telegram">
        <img src="{{ asset('assets/images/social/telegram.svg') }}" alt="Telegram">
    </a>
    @endif
    @if($support)
    <a href="{{ $support }}" class="float-btn live-float" @if($support !== '#') target="_blank" rel="noopener noreferrer" @endif aria-label="Customer Support">
        <img src="{{ asset('assets/images/social/support.svg') }}" alt="Support">
    </a>
    @endif
</div>
@endif
