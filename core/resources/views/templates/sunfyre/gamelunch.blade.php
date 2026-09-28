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
        display: flex;
        flex-direction: column;
    }

    .game-topbar {
        flex: 0 0 auto;
        z-index: 12010;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        min-height: 52px;
        padding: 8px 12px;
        padding-top: calc(8px + env(safe-area-inset-top, 0px));
        background: #012b27;
        border-bottom: 2px solid #e8b84a;
    }

    .game-bal {
        margin-right: auto;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        justify-content: center;
        line-height: 1.15;
        color: #fff;
        min-width: 0;
    }

    .game-bal__label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.3px;
        color: #e8b84a;
    }

    .game-bal__amount {
        display: inline-flex;
        align-items: baseline;
        gap: 6px;
        font-size: 16px;
        font-weight: 800;
        color: #fff;
    }

    .game-bal__cur {
        font-size: 12px;
        font-weight: 700;
        color: #e8b84a;
    }

    .game-bal .js-live-balance.live-balance-flash { color: #4ade80 !important; }

    .game-refresh {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        border: 1px solid rgba(232, 184, 74, 0.55);
        background: #064e46;
        color: #e8b84a;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        cursor: pointer;
        flex-shrink: 0;
    }

    .game-refresh.is-spinning i {
        animation: game-spin 0.7s linear;
    }

    @keyframes game-spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
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

    .game-stage {
        position: relative;
        flex: 1 1 auto;
        min-height: 0;
    }

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
        @auth
        <div class="game-bal" title="@lang('Site wallet')">
            <span class="game-bal__label">@lang('Main')</span>
            <span class="game-bal__amount">
                <span class="js-live-balance" data-live-balance="raw">{{ showAmount(auth()->user()->balance, currencyFormat: false) }}</span>
                <span class="game-bal__cur">{{ __(gs('cur_text')) }}</span>
            </span>
        </div>
        <button type="button" class="game-refresh js-live-balance-refresh" title="@lang('Refresh')" aria-label="@lang('Refresh')">
            <i class="fas fa-sync-alt"></i>
        </button>
        @endauth
    </div>

    <div class="game-stage">
    <div class="loading" id="loading">
        <div class="spinner"></div>
        <div class="loading-text">Loading Game...</div>
    </div>

    <iframe id="gameIframe" class="game-iframe" src="{{ $game_url }}" allowfullscreen></iframe>
    </div>
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

    document.querySelectorAll('.game-refresh').forEach(function (btn) {
        btn.addEventListener('click', function () {
            btn.classList.remove('is-spinning');
            void btn.offsetWidth;
            btn.classList.add('is-spinning');
        });
    });
</script>
@endsection
