@php
    $socialLinks = gs('social_links');
    $wa = trim(@$socialLinks->whatsapp ?? '');
    $tg = trim(@$socialLinks->telegram ?? '');
    $support = trim(@$socialLinks->support ?? '');
@endphp
@if($wa || $tg || $support)
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
    @if($support)
    <a href="{{ $support }}" class="float-btn live-float" target="_blank" rel="noopener noreferrer" aria-label="Support">
        <img src="{{ asset('assets/images/social/support.svg') }}" alt="Support">
    </a>
    @endif
</div>
@endif
