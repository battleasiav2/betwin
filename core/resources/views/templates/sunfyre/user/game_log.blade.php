@extends($activeTemplate . 'layouts.master')
@section('content')

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

<style>
    :root {
        --bg-color: #0b0f14;
        --header-color: #e8b84a;
        --accent-color: #2dd4a8;
        --card-bg: #151b24;
        --text-main: #e8eef5;
        --text-muted: #8b97a8;
        --border-color: rgba(255, 255, 255, 0.08);
        --blue-light: rgba(45, 212, 168, 0.12);
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    html, body { max-width: 100vw; overflow-x: hidden; touch-action: manipulation; }

    body { 
        background-color: var(--bg-color); 
        color: var(--text-main); 
        font-family: 'Roboto', sans-serif; 
        padding-bottom: 90px; 
        -webkit-tap-highlight-color: transparent;
        user-select: none;
    }

    .game-log-wrapper {
        min-height: 100vh;
    }

    /* ─── HEADER ─── */
    .header-bg { 
        background: linear-gradient(135deg, #0f1419 0%, #151b24 55%, #1a2330 100%);
        padding: 16px; 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        position: sticky; 
        top: 0; 
        z-index: 50; 
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .header-bg a {
        color: #ffffff;
        font-size: 20px;
        padding: 4px;
        text-decoration: none;
    }
    .header-bg h1 {
        color: #ffffff;
        font-weight: 700;
        font-size: 15px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* ─── TABS ─── */
    .tabs-container { 
        display: flex; 
        background-color: var(--card-bg); 
        border-bottom: 1px solid var(--border-color); 
        position: sticky; 
        top: 56px; 
        z-index: 40;
        box-shadow: 0 2px 5px rgba(0,0,0,0.02);
    }
    .tab-btn { 
        flex: 1; 
        text-align: center; 
        padding: 14px 0; 
        font-size: 13px; 
        font-weight: 700; 
        color: var(--text-muted); 
        cursor: pointer; 
        position: relative; 
        transition: 0.2s;
        text-transform: uppercase; 
        letter-spacing: 0.5px;
        text-decoration: none;
        background: none;
        border: none;
    }
    .tab-btn.active { color: var(--header-color); font-weight: 900; }
    .tab-btn.active::after {
        content: ''; 
        position: absolute; 
        bottom: 0; 
        left: 0; 
        width: 100%; 
        height: 3px;
        background-color: var(--accent-color);
    }

    /* ─── FILTER BAR ─── */
    .filter-bar { 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        padding: 12px 16px; 
        background-color: #0f1419; 
        border-bottom: 1px solid var(--border-color); 
        position: sticky;
        top: 108px;
        z-index: 30; 
    }
    .date-btn { 
        background-color: var(--card-bg); 
        color: var(--header-color); 
        font-size: 12px; 
        font-weight: 700; 
        padding: 8px 14px; 
        border-radius: 6px; 
        border: 1px solid rgba(45,212,168,0.3); 
        display: flex; 
        align-items: center; 
        gap: 6px; 
        cursor: pointer;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
    }
    .filter-icon-btn { 
        color: var(--header-color); 
        font-size: 15px; 
        cursor: pointer; 
        padding: 8px; 
        background: var(--card-bg); 
        border: 1px solid var(--border-color);
        border-radius: 6px; 
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
    }

    /* ─── DROPDOWN ─── */
    .dropdown-menu { 
        display: none; 
        position: absolute; 
        top: 55px; 
        left: 16px; 
        background-color: var(--card-bg); 
        border: 1px solid var(--border-color); 
        border-radius: 8px; 
        width: 180px; 
        box-shadow: 0 10px 25px rgba(0,0,0,0.1); 
        z-index: 50; 
        overflow: hidden;
    }
    .dropdown-menu.show { display: block; animation: fadeIn 0.2s ease; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
    
    .dropdown-item { 
        display: block; 
        padding: 12px 16px; 
        color: var(--text-main); 
        font-size: 12.5px; 
        font-weight: 600; 
        border-bottom: 1px solid var(--border-color); 
        cursor: pointer; 
        text-decoration: none;
    }
    .dropdown-item:last-child { border-bottom: none; }
    .dropdown-item:hover { background-color: var(--blue-light); color: var(--header-color); }

    /* ─── TABLE HEADER ─── */
    .table-header { 
        display: grid; 
        grid-template-columns: 1fr 1.2fr 0.8fr 1fr; 
        background-color: rgba(45,212,168,0.12); 
        padding: 12px 6px; 
        font-size: 11px; 
        font-weight: 800; 
        color: var(--header-color); 
        text-align: center; 
        border-bottom: 2px solid #bae6fd; 
        text-transform: uppercase;
    }
    .col-item { 
        border-right: 1px solid #bae6fd; 
        padding: 0 4px;
    }
    .col-item:last-child { border-right: none; }
    
    /* ─── DATA ROW ─── */
    .data-row { 
        display: grid; 
        grid-template-columns: 1fr 1.2fr 0.8fr 1fr; 
        padding: 14px 6px; 
        font-size: 12px; 
        text-align: center; 
        border-bottom: 1px solid var(--border-color); 
        align-items: center; 
        background-color: var(--card-bg);
        transition: background 0.2s;
    }
    .data-row:active { background-color: #0f1419; }
    
    .profit-win { 
        color: #166534; 
        background: #dcfce7; 
        padding: 4px 6px; 
        border-radius: 4px; 
        border: 1px solid #bbf7d0; 
        display: inline-block; 
        width: 100%;
        font-size: 11px;
        font-weight: 700;
    }
    .profit-loss { 
        color: #991b1b; 
        background: #fee2e2; 
        padding: 4px 6px; 
        border-radius: 4px; 
        border: 1px solid #fecaca; 
        display: inline-block; 
        width: 100%;
        font-size: 11px;
        font-weight: 700;
    }

    /* ─── EMPTY STATE ─── */
    .no-data { 
        display: flex; 
        flex-direction: column; 
        align-items: center; 
        justify-content: center; 
        min-height: 40vh; 
        opacity: 0.8; 
        background: #151b24;
    }
    .no-data .empty-icon {
        width: 64px;
        height: 64px;
        background: #f3f4f6;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
        border: 1px solid #e5e7eb;
    }
    .no-data .empty-icon i {
        font-size: 28px;
        color: #d1d5db;
    }
    .no-data p {
        font-size: 12px;
        color: #9ca3af;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* ─── MODAL ─── */
    .modal-overlay {
        position: fixed;
        inset: 0;
        z-index: 100;
        background: rgba(0,0,0,0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
        backdrop-filter: blur(4px);
    }
    .modal-overlay.hidden { display: none; }
    .modal-box {
        background: #151b24;
        border-radius: 12px;
        padding: 24px;
        text-align: center;
        width: 100%;
        max-width: 360px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        border: 1px solid #e5e7eb;
    }
    .modal-icon-wrap {
        width: 80px;
        height: 80px;
        background: #fef2f2;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        border: 1px solid #fecaca;
        position: relative;
    }
    .modal-icon-wrap i.fa-filter {
        font-size: 40px;
        color: #fca5a5;
    }
    .modal-close-btn {
        width: 100%;
        background: #e8b84a;
        color: #ffffff;
        font-weight: 700;
        padding: 12px 32px;
        border-radius: 8px;
        font-size: 14px;
        border: none;
        cursor: pointer;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }
    .modal-close-btn:active { background: #0f1419; }

    /* ─── SUMMARY BAR ─── */
    .fixed-bottom-summary {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        z-index: 90;
        background: #151b24;
        box-shadow: 0 -4px 12px rgba(0,0,0,0.08);
        border-top: 1px solid var(--border-color);
    }
    .summary-inner {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0;
    }
    .summary-item {
        padding: 10px 16px;
        text-align: center;
        border-right: 1px solid var(--border-color);
    }
    .summary-item:last-child { border-right: none; }
    .summary-value {
        font-size: 14px;
        font-weight: 900;
    }
    .summary-value.green { color: #166534; }
    .summary-value.red { color: #991b1b; }
    .summary-label {
        font-size: 10px;
        color: var(--text-muted);
        text-transform: uppercase;
        font-weight: 600;
        margin-top: 2px;
    }

    /* ─── BOTTOM NAV ─── */
    .bottom-nav-container {
        position: fixed;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100%;
        max-width: 480px;
        z-index: 10000;
        padding: 0 10px 8px 10px;
        background: #0b0f14;
    }

    .bottom-nav {
        width: 100%;
        height: 58px;
        background: linear-gradient(180deg, #1a2330 0%, #151b24 100%);
        border-radius: 999px;
        border: 1.5px solid #2a3544;
        box-shadow:
            0 0 0 2px #0b0f14,
            inset 0 1px 0 rgba(45,212,168,0.18),
            0 -2px 0 0 #2dd4a8,
            0 4px 24px rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: space-around;
        padding: 0 6px;
        position: relative;
    }

    .nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 3px;
        flex: 1;
        text-decoration: none !important;
        color: #8b97a8;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.2px;
        padding: 6px 0;
        transition: color 0.2s;
        position: relative;
    }

    .nav-item.active { color: #e8b84a; }
    .nav-item.active span { border-bottom: 2px solid #e8b84a; padding-bottom: 1px; }

    .nav-item i { font-size: 20px; }
    .nav-item span { font-size: 10px; font-weight: 700; }

    .center-item {
        position: relative;
        flex: 1;
        justify-content: flex-end;
        padding-bottom: 0;
    }

    .center-icon-circle {
        width: 54px;
        height: 54px;
        border-radius: 50%;
        background: linear-gradient(145deg, #2dd4a8, #1fa88a);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow:
            0 0 0 3px #0b0f14,
            0 0 0 5px #2dd4a8,
            0 6px 20px rgba(45,212,168,0.55);
        font-size: 22px;
        color: #fff;
        margin-top: -18px;
        border: none;
    }

    @media (min-width: 900px) {
        .bottom-nav-container { max-width: 600px; }
        .filter-bar { top: 108px; }
    }
</style>

<div class="game-log-wrapper">

    <!-- HEADER -->
    <div class="header-bg">
        <a href="{{ route('user.account') }}"><i class="fas fa-chevron-left"></i></a>
        <h1>Betting Records</h1>
        <a href="{{ route('user.home') }}"><i class="fas fa-times"></i></a>
    </div>

    <!-- TABS -->
    <div class="tabs-container">
        <a href="{{ route('user.game.log', ['tab' => 'settled', 'days' => request()->days, 'provider' => request()->provider]) }}" 
           class="tab-btn {{ request()->tab != 'unsettled' ? 'active' : '' }}">Settled</a>
        <a href="{{ route('user.game.log', ['tab' => 'unsettled', 'days' => request()->days, 'provider' => request()->provider]) }}" 
           class="tab-btn {{ request()->tab == 'unsettled' ? 'active' : '' }}">Unsettled</a>
    </div>

    <!-- FILTER BAR -->
    <div class="filter-bar" style="position: relative;">
        <button class="date-btn" onclick="toggleDropdown()">
            <i class="far fa-calendar-alt" style="color:#e8b84a;"></i>
            <span id="selectedDate">
                @if($days == 'today') Today
                @elseif($days == 'yesterday') Yesterday
                @elseif($days == '7days') Last 7 days
                @elseif($days == '30days') Last 1 month
                @else All time @endif
            </span> 
            <i class="fas fa-caret-down" style="margin-left:4px; font-size:10px;"></i>
        </button>
        
        <div id="dateDropdown" class="dropdown-menu">
            <a href="{{ route('user.game.log', ['days' => 'today', 'provider' => request()->provider, 'tab' => request()->tab]) }}" class="dropdown-item">Today</a>
            <a href="{{ route('user.game.log', ['days' => 'yesterday', 'provider' => request()->provider, 'tab' => request()->tab]) }}" class="dropdown-item">Yesterday</a>
            <a href="{{ route('user.game.log', ['days' => '7days', 'provider' => request()->provider, 'tab' => request()->tab]) }}" class="dropdown-item">Last 7 days</a>
            <a href="{{ route('user.game.log', ['days' => '30days', 'provider' => request()->provider, 'tab' => request()->tab]) }}" class="dropdown-item">Last 1 month</a>
            <a href="{{ route('user.game.log', ['provider' => request()->provider, 'tab' => request()->tab]) }}" class="dropdown-item">All time</a>
        </div>

        <button class="filter-icon-btn" onclick="openApiModal()">
            <i class="fas fa-filter"></i>
        </button>
    </div>

    <!-- TABLE HEADER -->
    <div class="table-header">
        <div class="col-item">Platform</div>
        <div class="col-item">Game Type</div>
        <div class="col-item">Turnover</div>
        <div class="col-item">Profit/Loss</div>
    </div>

    <!-- DATA ROWS -->
    <div class="min-h-[50vh]" style="background:#fff;">
        @forelse($logs as $log)
            @php
                $isWin = ($log->win_amo > $log->invest || $log->win_status != 0);
                $profitLoss = $log->win_amo - $log->invest;
                $displayName = $log->game ? $log->game->name : ($log->game_name ?? 'Game Result');
                $provider = $log->provider ?? ($log->game->provider ?? 'N/A');
            @endphp
            <div class="data-row">
                <div style="font-weight:700; font-size:11px;">{{ strtoupper($provider) }}</div>
                <div style="font-size:11px; color:#4b5563;">{{ __($displayName) }}</div>
                <div style="font-weight:700;">৳{{ number_format($log->invest, 2) }}</div>
                <div>
                    <span class="{{ $isWin ? 'profit-win' : 'profit-loss' }}">
                        {{ $isWin ? '+' : '' }}{{ number_format($profitLoss, 2) }}
                    </span>
                </div>
            </div>
        @empty
            <div class="no-data">
                <div class="empty-icon">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <p>No Records Found</p>
            </div>
        @endforelse
    </div>

    <!-- PAGINATION -->
    @if($logs->hasPages())
        <div style="padding: 16px; background:#fff;">
            {{ paginateLinks($logs) }}
        </div>
    @endif

</div>

<!-- SUMMARY BAR -->
<div class="fixed-bottom-summary" style="bottom: 70px;">
    <div class="summary-inner">
        <div class="summary-item">
            <div class="summary-value green">৳{{ number_format($widget['bet_amount'] ?? 0, 2) }}</div>
            <div class="summary-label">Bet Amount</div>
        </div>
        <div class="summary-item">
            <div class="summary-value green">৳{{ number_format($widget['valid_bet'] ?? 0, 2) }}</div>
            <div class="summary-label">Valid Bet</div>
        </div>
        <div class="summary-item">
            <div class="summary-value green">৳{{ number_format($widget['winnings'] ?? 0, 2) }}</div>
            <div class="summary-label">Winnings</div>
        </div>
        <div class="summary-item">
            <div class="summary-value red">৳{{ number_format($widget['profit_loss'] ?? 0, 2) }}</div>
            <div class="summary-label">Profit/Loss</div>
        </div>
    </div>
</div>

<!-- FILTER MODAL -->
<div id="apiModal" class="modal-overlay hidden">
    <div class="modal-box">
        <div class="modal-icon-wrap">
            <i class="fas fa-filter"></i>
        </div>
        <h3 style="color:#e8b84a; font-size:18px; font-weight:900; margin-bottom:8px; text-transform:uppercase; letter-spacing:0.5px;">
            Filter Not Available
        </h3>
        <p style="font-size:12px; color:#6b7280; margin-bottom:24px; font-weight:500;">
            Advanced filtering options are currently disabled.
        </p>
        <button onclick="document.getElementById('apiModal').classList.add('hidden')" class="modal-close-btn">
            Close
        </button>
    </div>
</div>

<!-- BOTTOM NAVIGATION -->
<div class="bottom-nav-container">
    <div class="bottom-nav">
        <a href="{{ route('user.home') }}" class="nav-item">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('user.promotions') }}" class="nav-item">
            <i class="fas fa-gift"></i>
            <span>Promotion</span>
        </a>
        <a href="{{ route('user.referrals') }}" class="nav-item center-item">
            <div class="center-icon-circle"><i class="fas fa-share-nodes"></i></div>
            <span>Invite</span>
        </a>
        <a href="{{ route('user.redeem.index') }}" class="nav-item">
            <i class="fas fa-trophy"></i>
            <span>Reward</span>
        </a>
        <a href="{{ route('user.account') }}" class="nav-item">
            <i class="fas fa-user-circle"></i>
            <span>Member</span>
        </a>
    </div>
</div>

<script>
    function toggleDropdown() {
        const dropdown = document.getElementById('dateDropdown');
        dropdown.classList.toggle('show');
    }

    function openApiModal() {
        document.getElementById('apiModal').classList.remove('hidden');
    }

    window.onclick = function(event) {
        if (!event.target.closest('.date-btn')) {
            var dropdowns = document.getElementsByClassName("dropdown-menu");
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    }
</script>

@endsection