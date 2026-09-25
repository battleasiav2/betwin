@extends('templates.sunfyre.layouts.master')

@section('content')
<style>
    body.is-game-play {
        margin: 0 !important;
        padding: 0 !important;
        overflow: hidden !important;
        height: 100vh !important;
        background: #000 !important;
    }

    body.is-game-play .site-header,
    body.is-game-play .m-dock,
    body.is-game-play #sidebar,
    body.is-game-play #sidebarOverlay,
    body.is-game-play .mobile-bottom-nav,
    body.is-game-play .main-footer-section {
        display: none !important;
    }

    .game-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 12000;
        background: #000;
    }

    .game-topbar {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        z-index: 12010;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 8px 12px;
        padding-top: calc(8px + env(safe-area-inset-top, 0px));
        background: linear-gradient(180deg, rgba(0,0,0,0.82) 0%, rgba(0,0,0,0.35) 70%, transparent 100%);
        pointer-events: none;
    }

    .game-topbar a,
    .game-topbar .game-bal {
        pointer-events: auto;
    }

    .game-bal {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(18, 59, 102, 0.95);
        color: #fff;
        font-weight: 800;
        font-size: 13px;
        padding: 7px 12px;
        border-radius: 999px;
        border: 1px solid rgba(255,255,255,0.18);
        min-width: 120px;
        justify-content: center;
    }

    .game-bal i { color: #f4b942; }
    .game-bal .js-live-balance.live-balance-flash { color: #4ade80 !important; }

    .game-home-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(37, 99, 235, 0.95);
        color: #fff;
        text-decoration: none;
        font-weight: 800;
        font-size: 12px;
        padding: 7px 12px;
        border-radius: 999px;
    }

    .loading {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
    }

    .spinner {
        width: 40px;
        height: 40px;
        border: 3px solid #064e46;
        border-top: 3px solid #00d094;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 10px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .loading-text { color: #00d094; font-size: 14px; }

    .game-iframe {
        width: 100%;
        height: 100%;
        border: none;
        background: #000;
        display: none;
    }

    .game-iframe.show { display: block; }
</style>

<div class="game-container">
    <div class="game-topbar">
        <a href="{{ route('user.home') }}" class="game-home-btn" id="gameHomeBtn">
            <i class="fas fa-arrow-left"></i> @lang('Home')
        </a>
        @auth
        <div class="game-bal" title="@lang('Site wallet')">
            <i class="fas fa-wallet"></i>
            <span class="js-live-balance" data-live-balance="full">{{ showAmount(auth()->user()->balance) }} {{ __(gs('cur_text')) }}</span>
        </div>
        @endauth
    </div>

    <div class="loading" id="loading">
        <div class="spinner"></div>
        <div class="loading-text">Loading Game...</div>
    </div>

    <iframe id="gameIframe" class="game-iframe" src="{{ $game_url }}" allowfullscreen></iframe>
</div>

<script>
    document.body.classList.add('is-game-play');
    document.body.dataset.balancePollMs = '2500';

    const iframe = document.getElementById('gameIframe');
    const loading = document.getElementById('loading');

    iframe.onload = function () {
        loading.style.display = 'none';
        iframe.classList.add('show');
    };

    setTimeout(function () {
        if (!iframe.classList.contains('show')) {
            loading.style.display = 'none';
            iframe.classList.add('show');
        }
    }, 8000);

    if (window.Bet369LiveBalance && typeof window.Bet369LiveBalance.start === 'function') {
        window.Bet369LiveBalance.start(2500);
    }
</script>
@endsection
