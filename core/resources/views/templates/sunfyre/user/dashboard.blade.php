@extends($activeTemplate . 'layouts.master')
@section('content')

@php
    $apiControls = App\Models\GeneralSetting::first()->whereNotNull('id')->get();
    $gameStatus = Illuminate\Support\Facades\DB::table('api_game_controls')->get()->keyBy('slug');
@endphp

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="referrer" content="no-referrer">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

<style>
    :root {
        --bg-deep:    #0b0f14;
        --bg-main:    #0f1419;
        --bg-card:    #151b24;
        --bg-card2:   #1a2330;
        --teal:       #1fa88a;
        --teal-light: #2dd4a8;
        --gold:       #e8b84a;
        --gold-dark:  #c49a3a;
        --gold-text:  #e8b84a;
        --green-btn:  #1a9b7a;
        --text-main:  #e8eef5;
        --text-muted: #8b97a8;
        --border:     rgba(255,255,255,0.08);
        --glass:      rgba(21, 27, 36, 0.75);
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: 'Segoe UI', Arial, sans-serif;
        background: var(--bg-deep);
        color: var(--text-main);
        padding-bottom: 70px;
        min-height: 100vh;
        -webkit-tap-highlight-color: transparent;
        user-select: none;
        background-image: linear-gradient(135deg, #0b0f14 0%, #0f1419 50%, #0b0f14 100%);
    }

    .custom-home-wrapper {
        margin: 0 !important;
        padding: 0 !important;
        width: 100%;
    }

    /* ─── ANNOUNCEMENT BAR ─── */
    .announce-bar {
        background: rgba(255,255,255,0.04); border-bottom: 1px solid var(--border);
        padding: 7px 14px; display: flex; align-items: center; gap: 8px;
    }
    .announce-bar .ann-icon { font-size: 14px; color: var(--gold-text); flex-shrink: 0; }
    .announce-bar marquee { font-size: 12px; color: var(--text-muted); font-weight: 500; }

    /* ─── SLIDER ─── */
    .slider-wrap { padding: 10px 10px 4px; }
    .swiper.mainSlider { border-radius: 12px; overflow: hidden; }
    .mainSlider .home-banner {
        width: 100%; height: 170px; display: flex !important; border-radius: 12px;
    }
    .home-banner {
        position: relative;
        overflow: hidden;
        align-items: center;
        padding: 0 16px;
        isolation: isolate;
        background: #0b0f14;
    }
    .home-banner .hb-art {
        position: absolute !important;
        inset: 0 !important;
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
        object-position: 72% center !important;
        opacity: 0.42 !important;
        border-radius: 12px !important;
        z-index: 0 !important;
        pointer-events: none;
        filter: saturate(0.95) contrast(1.05);
    }
    .home-banner::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            linear-gradient(90deg, rgba(11,15,20,0.92) 0%, rgba(11,15,20,0.78) 34%, rgba(11,15,20,0.35) 62%, rgba(11,15,20,0.18) 100%),
            radial-gradient(ellipse 55% 80% at 12% 40%, rgba(232,184,74,0.12), transparent 50%);
        z-index: 1;
        pointer-events: none;
        border-radius: 12px;
    }
    .hb-content { position: relative; z-index: 3; max-width: 62%; }
    .hb-kicker {
        display: inline-block;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 1.4px;
        text-transform: uppercase;
        color: #0b0f14;
        background: linear-gradient(90deg, #f0d078, #2dd4a8);
        padding: 3px 8px;
        border-radius: 4px;
        margin-bottom: 6px;
    }
    .hb-title {
        font-size: 20px;
        font-weight: 900;
        line-height: 1.15;
        letter-spacing: 0.2px;
        color: #e8eef5;
        margin: 0 0 4px;
        text-shadow: 0 2px 12px rgba(0,0,0,0.45);
    }
    .hb-title span {
        background: linear-gradient(90deg, #f0d078, #e8b84a, #2dd4a8);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }
    .hb-sub {
        font-size: 11px;
        font-weight: 600;
        color: rgba(232,238,245,0.72);
        margin: 0;
        letter-spacing: 0.2px;
    }
    .hb-games {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        z-index: 2;
        display: flex;
        align-items: flex-end;
        gap: 0;
        pointer-events: none;
    }
    .hb-games img.hb-g {
        width: 54px !important;
        height: 54px !important;
        object-fit: cover !important;
        border-radius: 10px !important;
        opacity: 0.55 !important;
        border: 1px solid rgba(255,255,255,0.14);
        box-shadow: 0 6px 14px rgba(0,0,0,0.4);
        background: rgba(21,27,36,0.6);
    }
    .hb-games img.hb-g:nth-child(1) {
        width: 68px !important;
        height: 68px !important;
        opacity: 0.7 !important;
        transform: translateY(-6px) rotate(-6deg);
        z-index: 3;
    }
    .hb-games img.hb-g:nth-child(2) {
        opacity: 0.58 !important;
        transform: translateY(8px) rotate(5deg);
        z-index: 2;
        margin-left: -14px;
    }
    .hb-games img.hb-g:nth-child(3) {
        opacity: 0.48 !important;
        transform: translateY(-2px) rotate(-3deg);
        z-index: 1;
        margin-left: -12px;
    }
    .swiper-pagination-bullet {
        background: rgba(255,255,255,0.3) !important; opacity: 1 !important;
        width: 6px !important; height: 6px !important; transition: all 0.3s;
    }
    .swiper-pagination-bullet-active {
        background: var(--gold-text) !important; width: 18px !important; border-radius: 3px !important;
    }

    /* ─── DEPOSIT & WITHDRAW — glass action tiles ─── */
    .quick-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        padding: 12px 12px 6px;
    }
    .qa-btn {
        position: relative;
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 14px;
        border-radius: 14px;
        font-size: 14px;
        font-weight: 800;
        text-decoration: none;
        letter-spacing: 0.3px;
        overflow: hidden;
        border: 1px solid rgba(255,255,255,0.08);
        background: #151b24;
        color: #e8eef5;
        box-shadow: none;
        transition: transform 0.15s ease, border-color 0.15s ease, background 0.15s ease;
    }
    .qa-btn::before {
        content: '';
        position: absolute;
        inset: 0 auto 0 0;
        width: 3px;
        border-radius: 14px 0 0 14px;
    }
    .qa-btn i {
        width: 38px;
        height: 38px;
        border-radius: 11px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        flex-shrink: 0;
    }
    .qa-btn.deposit {
        background: linear-gradient(135deg, rgba(45,212,168,0.14) 0%, #151b24 55%);
        border-color: rgba(45,212,168,0.28);
        color: #e8eef5;
        box-shadow: none;
        border-bottom: 1px solid rgba(45,212,168,0.28);
    }
    .qa-btn.deposit::before { background: #2dd4a8; }
    .qa-btn.deposit i {
        background: rgba(45,212,168,0.18);
        color: #2dd4a8;
        box-shadow: inset 0 0 0 1px rgba(45,212,168,0.35);
    }
    .qa-btn.withdraw {
        background: linear-gradient(135deg, rgba(232,184,74,0.14) 0%, #151b24 55%);
        border-color: rgba(232,184,74,0.28);
        color: #e8eef5;
        box-shadow: none;
        border-bottom: 1px solid rgba(232,184,74,0.28);
    }
    .qa-btn.withdraw::before { background: #e8b84a; }
    .qa-btn.withdraw i {
        background: rgba(232,184,74,0.18);
        color: #e8b84a;
        box-shadow: inset 0 0 0 1px rgba(232,184,74,0.35);
    }
    .qa-btn:active { transform: scale(0.98); }

    /* ─── SECTION HEADER ─── */
    .sec-header { display: flex; align-items: center; justify-content: space-between; padding: 14px 12px 8px; }
    .sec-title {
        display: flex; align-items: center; gap: 8px; font-size: 16px; font-weight: 800;
        color: var(--gold-text); text-transform: uppercase; letter-spacing: 0.5px;
    }
    .sec-title i { font-size: 18px; color: var(--gold-text); }

    .btn-see-all {
        padding: 6px 13px; border-radius: 7px; font-size: 12px; font-weight: 800;
        color: var(--gold-text);
        background: linear-gradient(180deg, rgba(232,184,74,0.18) 0%, rgba(232,184,74,0.10) 100%);
        border: 1px solid rgba(232,184,74,0.4); border-bottom: 2px solid rgba(232,184,74,0.6);
        text-decoration: none; transition: all 0.15s; box-shadow: 0 3px 0 rgba(0,0,0,0.3);
        display: inline-flex; align-items: center;
    }
    .btn-see-all:active { transform: translateY(2px); box-shadow: 0 1px 0 rgba(0,0,0,0.3); }

    /* ─── GAME GRID ─── */
    .games-section { padding: 0 10px; margin-bottom: 6px; }
    .game-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; }
    @media (min-width: 600px) { .game-grid { grid-template-columns: repeat(4, 1fr); } }
    @media (min-width: 900px) {
        .game-grid { grid-template-columns: repeat(6, 1fr); }
        .mainSlider .home-banner { height: 240px; }
        .hb-title { font-size: 28px; }
        .hb-sub { font-size: 13px; }
        .hb-kicker { font-size: 10px; padding: 4px 10px; }
        .hb-games img.hb-g { width: 72px !important; height: 72px !important; }
        .hb-games img.hb-g:nth-child(1) { width: 90px !important; height: 90px !important; }
        .home-banner .hb-art { opacity: 0.48 !important; }
    }

    .game-card {
        position: relative; border-radius: 10px; overflow: hidden; background: var(--bg-card);
        border: 1px solid rgba(255,255,255,0.07); border-bottom: 2px solid rgba(255,255,255,0.12);
        transition: all 0.2s; text-decoration: none; display: block; box-shadow: 0 4px 0 rgba(0,0,0,0.4);
    }
    .game-card:active { transform: scale(0.94) translateY(3px); border-color: var(--teal-light); box-shadow: 0 1px 0 rgba(0,0,0,0.4); }
    .game-card-img { width: 100%; aspect-ratio: 1; object-fit: cover; display: block; }

    .game-card-fav {
        position: absolute; top: 5px; right: 5px; width: 26px; height: 26px;
        border-radius: 50%; background: rgba(0,0,0,0.45); backdrop-filter: blur(4px);
        display: flex; align-items: center; justify-content: center;
        color: rgba(255,255,255,0.6); font-size: 13px; cursor: pointer;
        z-index: 5; transition: color 0.2s, background 0.2s; border: none; flex-shrink: 0;
    }
    .game-card-fav.active { color: var(--gold-text); background: rgba(232,184,74,0.18); }

    .game-card-name {
        font-size: 10px; font-weight: 700; color: var(--text-main); padding: 5px 6px;
        text-align: center; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }

    /* ─── STATUS TAGS ─── */
    .game-card[data-status="2"]::before {
        content: 'কাজ চলছে';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.75);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 5;
        backdrop-filter: blur(2px);
        color: #ffffff;
        font-weight: 700;
        font-size: 13px;
        border: 1px solid rgba(211, 47, 47, 0.5);
        border-radius: 10px;
    }

    .game-card[data-status="0"]::before,
    .game-card[data-status="3"]::before {
        content: 'শীঘ্রই আসছে';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.75);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 5;
        backdrop-filter: blur(2px);
        color: #ff9800;
        font-weight: 700;
        font-size: 13px;
        border: 1px solid rgba(245, 127, 23, 0.5);
        border-radius: 10px;
    }

    /* ─── CATEGORY NAV — underline rail ─── */
    .cat-nav-wrap {
        padding: 8px 10px 0; overflow-x: auto; white-space: nowrap;
        scrollbar-width: none; -ms-overflow-style: none;
    }
    .cat-nav-wrap::-webkit-scrollbar { display: none; }
    .cat-nav-inner {
        display: inline-flex;
        gap: 4px;
        padding: 4px;
        margin-bottom: 8px;
        border-radius: 12px;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.06);
    }
    .cat-pill {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 14px; border-radius: 9px; font-size: 11px; font-weight: 800;
        text-decoration: none; color: #8b97a8; letter-spacing: 0.6px;
        text-transform: uppercase;
        background: transparent; border: 1px solid transparent;
        transition: all 0.2s; white-space: nowrap; cursor: pointer;
        position: relative;
    }
    .cat-pill i { font-size: 12px; opacity: 0.85; }
    .cat-pill.active, .cat-pill:active {
        background: rgba(232,184,74,0.12);
        border-color: rgba(232,184,74,0.35);
        color: #e8b84a;
        box-shadow: none;
    }
    .cat-pill.active::after {
        content: '';
        position: absolute;
        left: 12px; right: 12px; bottom: 3px;
        height: 2px;
        border-radius: 2px;
        background: #e8b84a;
    }

    /* Category section */
    .cat-section {
        margin-bottom: 6px; background: rgba(21,27,36,0.45);
        border-top: 1px solid var(--border); border-bottom: 1px solid var(--border); padding-bottom: 8px;
    }

    /* ─── PROVIDER GRID ─── */
    .provider-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        padding: 0 10px;
    }
    @media (min-width: 600px) {
        .provider-grid { grid-template-columns: repeat(4, 1fr); }
    }
    @media (min-width: 900px) {
        .provider-grid { grid-template-columns: repeat(5, 1fr); }
    }

    .provider-card {
        background: var(--bg-card);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 12px;
        padding: 16px 8px;
        aspect-ratio: 1 / 0.9;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        box-shadow: 0 4px 0 rgba(0,0,0,0.3);
    }
    .provider-card:active {
        transform: scale(0.94) translateY(3px);
        border-color: var(--teal-light);
        box-shadow: 0 1px 0 rgba(0,0,0,0.3);
    }
    .provider-card img {
        height: 32px;
        max-width: 75%;
        object-fit: contain;
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.4));
    }
    .provider-card i {
        font-size: 28px;
        color: var(--text-main);
    }
    .provider-card span {
        color: var(--text-main);
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        text-align: center;
        line-height: 1.2;
    }

    /* ─── GAME CENTER ─── */
    .game-center { padding: 8px 12px 16px; }
    .game-center-title { font-size: 18px; font-weight: 800; color: var(--gold-text); margin-bottom: 12px; }
    .game-center-pills { display: flex; flex-wrap: wrap; gap: 8px; }
    .gc-pill {
        padding: 8px 18px; border-radius: 8px; font-size: 13px; font-weight: 700;
        color: var(--teal-light); border: 1px solid rgba(45,212,168,0.4);
        border-bottom: 2px solid rgba(45,212,168,0.5);
        background: linear-gradient(180deg, rgba(45,212,168,0.12) 0%, rgba(45,212,168,0.06) 100%);
        text-decoration: none; transition: all 0.15s; box-shadow: 0 3px 0 rgba(0,0,0,0.3);
    }
    .gc-pill:active { transform: translateY(2px); box-shadow: 0 1px 0 rgba(0,0,0,0.3); }

    /* ─── FLOATING SOCIAL BUTTONS ─── */
    .float-social-btns {
        position: fixed;
        right: 12px;
        bottom: 90px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .float-btn {
        width: 52px; height: 52px;
        border-radius: 50%;
        display: flex;
        align-items: center; justify-content: center;
        text-decoration: none;
        padding: 0;
        background: transparent !important;
        border: none;
        box-shadow: 0 6px 16px rgba(0,0,0,0.35);
        transition: transform 0.2s, box-shadow 0.2s;
        overflow: hidden;
    }
    .float-btn:active { transform: scale(0.92); }
    .float-btn img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
        display: block;
    }
    .float-btn.wa-float,
    .float-btn.fb-float,
    .float-btn.tg-float,
    .float-btn.live-float { background: transparent !important; }

    /* Bottom nav styles live in clean-dark.css (Crystal Rail) */

    .section-container { transition: opacity 0.3s ease; }
    .section-container.show-anim { animation: softFade 0.4s ease forwards; }

    @keyframes softFade {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .game-tag, .game-item__title, h4 { display: none !important; }
    .main-footer-section {
        margin-top: 25px;
        padding-bottom: 90px; /* clear fixed bottom nav */
    }
    .main-footer-section .footer-area {
        margin-top: 0 !important;
        overflow: hidden;
    }
    .main-footer-section .footer-area::before,
    .main-footer-section .footer-area::after {
        content: none !important;
        display: none !important;
    }
    .main-footer-section .footer-area__thumb {
        display: none !important;
    }

    /* ─── DESKTOP ─── */
    @media (min-width: 900px) {
        .bottom-nav-container { max-width: 600px; }
    }
</style>

<div class="custom-home-wrapper">

<!-- ANNOUNCEMENT -->
<div class="announce-bar">
    <i class="fas fa-bullhorn ann-icon"></i>
    <marquee scrollamount="4">{{ gs('announcement_text') }}</marquee>
</div>

<!-- SLIDER -->
<div class="slider-wrap">
    <div class="swiper mainSlider">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <div class="home-banner hb-1">
                    <img class="hb-art" src="{{ asset($activeTemplateTrue . 'images/banner/gaming-bg.png') }}" alt="" loading="lazy">
                    <div class="hb-content">
                        <span class="hb-kicker">Welcome</span>
                        <h3 class="hb-title"><span>BET369WIN</span></h3>
                        <p class="hb-sub">Premium slots, sports &amp; casino — play in style</p>
                    </div>
                    <div class="hb-games">
                        <img class="hb-g" src="https://ossimg.91admin123admin.com/91club/gamelogo/JILI/49.png" alt="" loading="lazy" referrerpolicy="no-referrer">
                        <img class="hb-g" src="https://ossimg.91admin123admin.com/91club/gamelogo/JILI/109.png" alt="" loading="lazy" referrerpolicy="no-referrer">
                        <img class="hb-g" src="https://ossimg.91admin123admin.com/91club/gamelogo/PG/126.png" alt="" loading="lazy" referrerpolicy="no-referrer">
                    </div>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="home-banner hb-2">
                    <img class="hb-art" src="{{ asset($activeTemplateTrue . 'images/banner/gaming-slots.png') }}" alt="" loading="lazy">
                    <div class="hb-content">
                        <span class="hb-kicker">Jackpot</span>
                        <h3 class="hb-title">Spin &amp; <span>Win Big</span></h3>
                        <p class="hb-sub">Hot slots every day — bigger pots, faster fun</p>
                    </div>
                    <div class="hb-games">
                        <img class="hb-g" src="https://ossimg.91admin123admin.com/91club/gamelogo/JILI/35.png" alt="" loading="lazy" referrerpolicy="no-referrer">
                        <img class="hb-g" src="https://ossimg.91admin123admin.com/91club/gamelogo/JILI/51.png" alt="" loading="lazy" referrerpolicy="no-referrer">
                        <img class="hb-g" src="https://ossimg.91admin123admin.com/91club/gamelogo/JILI/134.png" alt="" loading="lazy" referrerpolicy="no-referrer">
                    </div>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="home-banner hb-3">
                    <img class="hb-art" src="{{ asset($activeTemplateTrue . 'images/banner/gaming-sports.png') }}" alt="" loading="lazy">
                    <div class="hb-content">
                        <span class="hb-kicker">Sports</span>
                        <h3 class="hb-title">Bet Live. <span>Feel It</span></h3>
                        <p class="hb-sub">Football, cricket &amp; more — odds that move with you</p>
                    </div>
                    <div class="hb-games">
                        <img class="hb-g" src="https://spribe.co/assets/images/games/Av-new@2x.png?v=2.5.61" alt="" loading="lazy" referrerpolicy="no-referrer">
                        <img class="hb-g" src="https://ossimg.91admin123admin.com/91club/gamelogo/JILI/77.png" alt="" loading="lazy" referrerpolicy="no-referrer">
                        <img class="hb-g" src="https://ossimg.91admin123admin.com/91club/gamelogo/PG/74.png" alt="" loading="lazy" referrerpolicy="no-referrer">
                    </div>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="home-banner hb-4">
                    <img class="hb-art" src="{{ asset($activeTemplateTrue . 'images/banner/gaming-bg.png') }}" alt="" loading="lazy">
                    <div class="hb-content">
                        <span class="hb-kicker">Bonus</span>
                        <h3 class="hb-title">Deposit. <span>Play Fast</span></h3>
                        <p class="hb-sub">Instant top-up · smooth withdraw · secure wallet</p>
                    </div>
                    <div class="hb-games">
                        <img class="hb-g" src="https://ossimg.91admin123admin.com/91club/gamelogo/PG/135.png" alt="" loading="lazy" referrerpolicy="no-referrer">
                        <img class="hb-g" src="https://ossimg.91admin123admin.com/91club/gamelogo/JILI/103.png" alt="" loading="lazy" referrerpolicy="no-referrer">
                        <img class="hb-g" src="https://ossimg.91admin123admin.com/91club/gamelogo/JILI/110.png" alt="" loading="lazy" referrerpolicy="no-referrer">
                    </div>
                </div>
            </div>
        </div>
        <div class="swiper-pagination" style="bottom:10px"></div>
    </div>
</div>

<!-- DEPOSIT & WITHDRAW -->
<div class="quick-actions">
    <a href="{{ route('user.deposit.index') }}" class="qa-btn deposit">
        <i class="fas fa-plus-circle"></i> Deposit
    </a>
    <a href="{{ route('user.withdraw') }}" class="qa-btn withdraw">
        <i class="fas fa-arrow-up-from-bracket"></i> Withdraw
    </a>
</div>

<!-- CATEGORY PILLS -->
<nav class="cat-nav-wrap">
    <div class="cat-nav-inner">
        <a href="javascript:void(0)" class="cat-pill active" onclick="filterGames('hot', this)">
            <i class="fas fa-fire"></i> HOT
        </a>
        <a href="javascript:void(0)" class="cat-pill" onclick="filterGames('sports', this)">
            <i class="fas fa-futbol"></i> SPORTS
        </a>
        <a href="javascript:void(0)" class="cat-pill" onclick="filterGames('crash', this)">
            <i class="fas fa-chart-line"></i> CRASH
        </a>
        <a href="javascript:void(0)" class="cat-pill" onclick="filterGames('slot', this)">
            <i class="fas fa-dice"></i> SLOT
        </a>
        <a href="javascript:void(0)" class="cat-pill" onclick="filterGames('casino', this)">
            <i class="fas fa-video"></i> CASINO
        </a>
        <a href="javascript:void(0)" class="cat-pill" onclick="filterGames('table', this)">
            <i class="fas fa-table"></i> TABLE
        </a>
        <a href="javascript:void(0)" class="cat-pill" onclick="filterGames('fishing', this)">
            <i class="fas fa-fish"></i> FISHING
        </a>
        <a href="javascript:void(0)" class="cat-pill" onclick="filterGames('poker', this)">
            <i class="fas fa-spade"></i> POKER
        </a>
    </div>
</nav>

<!-- GAMES SECTIONS -->
<div id="gamesSections">
    <div class="section-container" data-provider="hot">
        <div class="sec-header">
            <div class="sec-title"><i class="fas fa-fire"></i> HOT GAMES</div>
            <a href="javascript:void(0)" class="btn-see-all" onclick="seeAll('hot')" style="display:none;">See All</a>
        </div>
        <div class="games-section" id="hot-wrapper" data-status="1">
            <div class="game-grid">@include($activeTemplate . 'partials.hot-games')</div>
        </div>
    </div>

    <div class="section-container" data-provider="sports" style="display:none;">
        <div class="sec-header">
            <div class="sec-title"><i class="fas fa-futbol"></i> SPORTS</div>
            <a href="javascript:void(0)" class="btn-see-all" onclick="seeAll('sports')">See All</a>
        </div>
        <div class="games-section" id="sports-wrapper" data-status="1">
            <div class="game-grid">@include($activeTemplate . 'partials.sports-games')</div>
        </div>
    </div>

    <div class="section-container" data-provider="crash" style="display:none;">
        <div class="sec-header">
            <div class="sec-title"><i class="fas fa-chart-line"></i> CRASH GAMES</div>
            <a href="javascript:void(0)" class="btn-see-all" onclick="seeAll('crash')">See All</a>
        </div>
        <div class="games-section" id="crash-wrapper" data-status="1">
            <div class="game-grid">@include($activeTemplate . 'partials.crash-games')</div>
        </div>
    </div>

    <div class="section-container" data-provider="casino" style="display:none;">
        <div class="sec-header">
            <div class="sec-title"><i class="fas fa-video"></i> CASINO</div>
            <a href="javascript:void(0)" class="btn-see-all" onclick="seeAll('casino')">See All</a>
        </div>
        <div class="games-section" id="casino-wrapper" data-status="{{ isset($gameStatus['evo']) ? $gameStatus['evo']->status : 1 }}">
            <div class="game-grid">@include($activeTemplate . 'partials.evo-games')</div>
        </div>
    </div>

    <div id="provider-grid-container" style="display:none;">
        <div class="sec-header">
            <div class="sec-title"><i class="fas fa-dice"></i> SLOT PROVIDERS</div>
        </div>

        <div class="provider-grid">
            @if(isset($gameStatus['jili']) && $gameStatus['jili']->status != 0)
            <div class="provider-card" data-key="jili" onclick="selectProvider('jili')">
                <img src="https://img.b6814jd.com/bjd/h5/assets/images/brand/white/provider-awcv2_jili.png?v=1781595979466&source=mcdsrc" alt="JILI">
                <span>JILI</span>
            </div>
            @endif
            @if(isset($gameStatus['pg']) && $gameStatus['pg']->status != 0)
            <div class="provider-card" data-key="pg" onclick="selectProvider('pg')">
                <img src="https://img.b6814jd.com/bjd/h5/assets/images/brand/white/provider-awcv2_pg.png?v=1781595979466&source=mcdsrc" alt="PG">
                <span>PG Soft</span>
            </div>
            @endif
            @if(isset($gameStatus['jdb']) && $gameStatus['jdb']->status != 0)
            <div class="provider-card" data-key="jdb" onclick="selectProvider('jdb')">
                <img src="https://img.b6814jd.com/bjd/h5/assets/images/brand/white/provider-awcv2_jdb.png?v=1781595979466&source=mcdsrc" alt="JDB">
                <span>JDB</span>
            </div>
            @endif
            @if(isset($gameStatus['cq9']) && $gameStatus['cq9']->status != 0)
            <div class="provider-card" data-key="cq9" onclick="selectProvider('cq9')">
                <img src="https://img.b6814jd.com/bjd/h5/assets/images/brand/white/provider-cq9.png?v=1781595979466&source=mcdsrc" alt="CQ9">
                <span>CQ9</span>
            </div>
            @endif
            @if(isset($gameStatus['idg']) && $gameStatus['idg']->status != 0)
            <div class="provider-card" data-key="idg" onclick="selectProvider('idg')">
                <img src="https://img.b6814jd.com/bjd/h5/assets/images/brand/white/provider-awcv2_dreamgaming.png?v=1781595979466&source=mcdsrc" alt="IDG">
                <span>IDG</span>
            </div>
            @endif
            @if(isset($gameStatus['km']) && $gameStatus['km']->status != 0)
            <div class="provider-card" data-key="km" onclick="selectProvider('km')">
                <img src="https://img.b6814jd.com/bjd/h5/assets/images/brand/white/provider-awcv2_kingmaker.png?v=1781595979466&source=mcdsrc" alt="KM">
                <span>KM</span>
            </div>
            @endif
            @if(isset($gameStatus['v8']) && $gameStatus['v8']->status != 0)
            <div class="provider-card" data-key="v8" onclick="selectProvider('v8')">
                <img src="https://img.b6814jd.com/bjd/h5/assets/images/brand/white/provider-awcv2_yesbingo.png?v=1781595979466&source=mcdsrc" alt="V8">
                <span>V8</span>
            </div>
            @endif
            @if(isset($gameStatus['mg']) && $gameStatus['mg']->status != 0)
            <div class="provider-card" data-key="mg" onclick="selectProvider('mg')">
                <img src="https://img.b6814jd.com/bjd/h5/assets/images/brand/white/provider-mg.png?v=1781595979466&source=mcdsrc" alt="MG">
                <span>MG</span>
            </div>
            @endif

            <div class="provider-card" data-key="arcade">
                <img src="https://img.b6814jd.com/bjd/h5/assets/images/brand/white/provider-rich88.png?v=1781595979466&source=mcdsrc" alt="Arcade">
                <span>Arcade</span>
            </div>
            <div class="provider-card" data-key="lottery">
                <img src="https://img.b6814jd.com/bjd/h5/assets/images/brand/white/provider-saba.png?v=1781595979466&source=mcdsrc" alt="Lottery">
                <span>Lottery</span>
            </div>
            <div class="provider-card" data-key="bingo">
                <img src="https://img.b6814jd.com/bjd/h5/assets/images/brand/white/provider-playngo.png?v=1781595979466&source=mcdsrc" alt="Bingo">
                <span>Bingo</span>
            </div>
            <div class="provider-card" data-key="live">
                <img src="https://img.b6814jd.com/bjd/h5/assets/images/brand/white/provider-awcv2_yl.png?v=1781595979466&source=mcdsrc" alt="Live">
                <span>Live</span>
            </div>
            <div class="provider-card" data-key="mini">
                <img src="https://img.b6814jd.com/bjd/h5/assets/images/brand/white/provider-awcv2_mimi.png?v=1781595979466&source=mcdsrc" alt="Mini Game">
                <span>Mini Game</span>
            </div>
        </div>
    </div>

    @if(isset($gameStatus['jili']) && $gameStatus['jili']->status != 0)
    <div class="section-container" data-provider="jili" style="display:none;">
        <div class="sec-header">
            <a href="javascript:void(0)" class="btn-see-all" onclick="backToProviders()"><i class="fas fa-arrow-left"></i> Back</a>
            <div class="sec-title"><i class="fas fa-fire"></i> JILI GAMES</div>
        </div>
        <div class="games-section" id="jili-wrapper" data-status="{{ $gameStatus['jili']->status }}">
            <div class="game-grid">@include($activeTemplate . 'partials.jili-games')</div>
        </div>
    </div>
    @endif

    @if(isset($gameStatus['pg']) && $gameStatus['pg']->status != 0)
    <div class="section-container" data-provider="pg" style="display:none;">
        <div class="sec-header">
            <a href="javascript:void(0)" class="btn-see-all" onclick="backToProviders()"><i class="fas fa-arrow-left"></i> Back</a>
            <div class="sec-title"><i class="fas fa-fire"></i> PG SOFT</div>
        </div>
        <div class="games-section" id="pg-wrapper" data-status="{{ $gameStatus['pg']->status }}">
            <div class="game-grid">@include($activeTemplate . 'partials.pg-games')</div>
        </div>
    </div>
    @endif

    @if(isset($gameStatus['jdb']) && $gameStatus['jdb']->status != 0)
    <div class="section-container" data-provider="jdb" style="display:none;">
        <div class="sec-header">
            <a href="javascript:void(0)" class="btn-see-all" onclick="backToProviders()"><i class="fas fa-arrow-left"></i> Back</a>
            <div class="sec-title"><i class="fas fa-fire"></i> JDB GAMES</div>
        </div>
        <div class="games-section" id="jdb-wrapper" data-status="{{ $gameStatus['jdb']->status }}">
            <div class="game-grid">@include($activeTemplate . 'partials.jdb-games')</div>
        </div>
    </div>
    @endif

    @if(isset($gameStatus['cq9']) && $gameStatus['cq9']->status != 0)
    <div class="section-container" data-provider="cq9" style="display:none;">
        <div class="sec-header">
            <a href="javascript:void(0)" class="btn-see-all" onclick="backToProviders()"><i class="fas fa-arrow-left"></i> Back</a>
            <div class="sec-title"><i class="fas fa-fire"></i> CQ9 GAMES</div>
        </div>
        <div class="games-section" id="cq9-wrapper" data-status="{{ $gameStatus['cq9']->status }}">
            <div class="game-grid">@include($activeTemplate . 'partials.cq9-games')</div>
        </div>
    </div>
    @endif

    @if(isset($gameStatus['idg']) && $gameStatus['idg']->status != 0)
    <div class="section-container" data-provider="idg" style="display:none;">
        <div class="sec-header">
            <a href="javascript:void(0)" class="btn-see-all" onclick="backToProviders()"><i class="fas fa-arrow-left"></i> Back</a>
            <div class="sec-title"><i class="fas fa-fire"></i> IDG GAMES</div>
        </div>
        <div class="games-section" id="idg-wrapper" data-status="{{ $gameStatus['idg']->status }}">
            <div class="game-grid">@include($activeTemplate . 'partials.idg-games')</div>
        </div>
    </div>
    @endif

    @if(isset($gameStatus['km']) && $gameStatus['km']->status != 0)
    <div class="section-container" data-provider="km" style="display:none;">
        <div class="sec-header">
            <a href="javascript:void(0)" class="btn-see-all" onclick="backToProviders()"><i class="fas fa-arrow-left"></i> Back</a>
            <div class="sec-title"><i class="fas fa-fire"></i> KM GAMES</div>
        </div>
        <div class="games-section" id="km-wrapper" data-status="{{ $gameStatus['km']->status }}">
            <div class="game-grid">@include($activeTemplate . 'partials.km-games')</div>
        </div>
    </div>
    @endif

    @if(isset($gameStatus['v8']) && $gameStatus['v8']->status != 0)
    <div class="section-container" data-provider="v8" style="display:none;">
        <div class="sec-header">
            <a href="javascript:void(0)" class="btn-see-all" onclick="backToProviders()"><i class="fas fa-arrow-left"></i> Back</a>
            <div class="sec-title"><i class="fas fa-fire"></i> V8 GAMES</div>
        </div>
        <div class="games-section" id="v8-wrapper" data-status="{{ $gameStatus['v8']->status }}">
            <div class="game-grid">@include($activeTemplate . 'partials.v8-games')</div>
        </div>
    </div>
    @endif

    @if(isset($gameStatus['mg']) && $gameStatus['mg']->status != 0)
    <div class="section-container" data-provider="mg" style="display:none;">
        <div class="sec-header">
            <a href="javascript:void(0)" class="btn-see-all" onclick="backToProviders()"><i class="fas fa-arrow-left"></i> Back</a>
            <div class="sec-title"><i class="fas fa-fire"></i> MG GAMES</div>
        </div>
        <div class="games-section" id="mg-wrapper" data-status="{{ $gameStatus['mg']->status }}">
            <div class="game-grid">@include($activeTemplate . 'partials.mg-games')</div>
        </div>
    </div>
    @endif
</div>

<!-- GAME CENTER -->
<div class="game-center">
    <div class="game-center-title">Game Center</div>
    <div class="game-center-pills">
        <a href="#" class="gc-pill">Slots</a>
        <a href="#" class="gc-pill">Live Casino</a>
        <a href="#" class="gc-pill">Sports</a>
        <a href="#" class="gc-pill">E-sports</a>
        <a href="#" class="gc-pill">Poker</a>
        <a href="#" class="gc-pill">Fish</a>
        <a href="#" class="gc-pill">Lottery</a>
    </div>
</div>

<div class="main-footer-section">
    @include($activeTemplate . 'partials.footer')
</div>

</div><!-- /custom-home-wrapper -->

<!-- FLOATING SOCIAL BUTTONS -->
@include($activeTemplate . 'partials.float_social')

<!-- BOTTOM NAVIGATION -->
<div class="bottom-nav-container">
    <div class="bottom-nav">
        <a href="{{ route('user.home') }}" class="nav-item {{ request()->routeIs('user.home') ? 'active' : '' }}">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('user.promotions') }}" class="nav-item">
            <i class="fas fa-gift"></i>
            <span>Promotion</span>
        </a>
        <a href="{{ route('user.referrals') }}" class="nav-item center-item {{ request()->routeIs('user.referrals') ? 'active' : '' }}">
            <div class="center-icon-circle"><i class="fas fa-share-nodes"></i></div>
            <span>Invite</span>
        </a>
        <a href="{{ route('user.redeem.index') }}" class="nav-item {{ request()->routeIs('user.redeem.index') ? 'active' : '' }}">
            <i class="fas fa-trophy"></i>
            <span>Reward</span>
        </a>
        <a href="{{ route('user.account') }}" class="nav-item {{ request()->routeIs('user.account') ? 'active' : '' }}">
            <i class="fas fa-user-circle"></i>
            <span>Member</span>
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

@endsection

@push('script')
<script>
    new Swiper('.mainSlider', {
        loop: true,
        autoplay: { delay: 3500, disableOnInteraction: false },
        pagination: { el: '.swiper-pagination', clickable: true }
    });

    const providerGridEl = document.querySelector('#provider-grid-container .provider-grid');
    const originalProviderCards = providerGridEl ? Array.from(providerGridEl.children) : [];

    function shuffleArray(arr) {
        for (let i = arr.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [arr[i], arr[j]] = [arr[j], arr[i]];
        }
        return arr;
    }

    function resetProviderGrid() {
        if (!providerGridEl) return;
        originalProviderCards.forEach(card => {
            card.style.display = '';
            providerGridEl.appendChild(card);
        });
    }

    function showRandomProviderGrid(excludeKeys) {
        if (!providerGridEl) return;
        let visibleCards = originalProviderCards.filter(card => !excludeKeys.includes(card.dataset.key));
        shuffleArray(visibleCards);
        visibleCards.forEach(card => {
            card.style.display = '';
            providerGridEl.appendChild(card);
        });
        originalProviderCards.forEach(card => {
            if (excludeKeys.includes(card.dataset.key)) {
                card.style.display = 'none';
            }
        });
    }

    function filterGames(category, btn) {
        document.querySelectorAll('.cat-pill').forEach(el => el.classList.remove('active'));
        btn.classList.add('active');

        requestAnimationFrame(() => {
            const sections = document.querySelectorAll('.section-container');
            sections.forEach(s => { s.style.display = 'none'; s.classList.remove('show-anim'); });
            document.getElementById('provider-grid-container').style.display = 'none';
            document.querySelector('.main-footer-section').style.display = 'none';
            document.querySelector('.game-center').style.display = 'none';

            if (category === 'slot') {
                document.getElementById('provider-grid-container').style.display = 'block';
                resetProviderGrid();
                return;
            }

            if (category === 'table' || category === 'fishing' || category === 'poker') {
                document.getElementById('provider-grid-container').style.display = 'block';
                showRandomProviderGrid(['jili', 'pg']);
                return;
            }

            let target = document.querySelector('.section-container[data-provider="' + category + '"]');
            if (target) {
                target.style.display = 'block';
                target.classList.add('show-anim');
                document.querySelector('.main-footer-section').style.display = 'block';
                document.querySelector('.game-center').style.display = 'block';
            }
        });
    }

    function selectProvider(provider) {
        document.getElementById('provider-grid-container').style.display = 'none';
        let target = document.querySelector('.section-container[data-provider="' + provider + '"]');
        if (target) {
            document.querySelectorAll('.section-container').forEach(s => { s.style.display = 'none'; s.classList.remove('show-anim'); });
            target.style.display = 'block';
            target.classList.add('show-anim');
            document.querySelector('.main-footer-section').style.display = 'none';
            document.querySelector('.game-center').style.display = 'none';
        }
    }

    function backToProviders() {
        document.querySelectorAll('.section-container').forEach(s => { s.style.display = 'none'; s.classList.remove('show-anim'); });
        document.getElementById('provider-grid-container').style.display = 'block';
    }

    function seeAll(provider) {
        // No specific swiper action needed as we are using grids
    }

    $(document).on('click', '.game-card', function(e) {
        let status = $(this).data('status');
        if (status && status != 1) {
            e.preventDefault();
            let msg = status == 2 ? "এই গেমটির কাজ চলছে। খুব শীঘ্রই ফিরবে!" : "এই গেমটি খুব শীঘ্রই আসছে। সাথে থাকুন!";
            if (typeof iziToast !== 'undefined') {
                iziToast.info({ message: msg, position: "topRight", timeout: 2000 });
            } else {
                alert(msg);
            }
        }
    });

</script>
@endpush
