@extends($activeTemplate . 'layouts.master')
@section('content')

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

<style>
    :root {
        --bg-color: #e8f0fa;
        --header-color: #154b77;
        --accent-color: #43a047;
        --card-bg: #ffffff;
        --text-main: #333333;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
        --blue-light: #e0f2fe;
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

    .ticket-index-wrapper {
        min-height: 100vh;
    }

    /* â”€â”€â”€ HEADER â”€â”€â”€ */
    .header-bg { 
        background: linear-gradient(135deg, #0f395c 0%, #1a5c92 100%);
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 16px;
        position: sticky;
        top: 0;
        z-index: 50;
    }
    .header-bg a {
        color: #ffffff;
        font-size: 20px;
        padding: 4px;
        text-decoration: none;
        transition: transform 0.15s;
    }
    .header-bg a:active { transform: scale(0.9); }
    .header-bg h1 {
        color: #ffffff;
        font-weight: 800;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 1px;
        flex: 1;
    }

    /* â”€â”€â”€ NEW TICKET BUTTON â”€â”€â”€ */
    .new-ticket-btn {
        background: linear-gradient(to bottom, #4caf50, #388e3c);
        color: #ffffff;
        font-weight: 700;
        font-size: 11px;
        padding: 8px 14px;
        border-radius: 6px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 6px rgba(67, 160, 71, 0.2);
        transition: all 0.15s;
        white-space: nowrap;
    }
    .new-ticket-btn:active { transform: scale(0.95); }

    /* â”€â”€â”€ TICKET CARD â”€â”€â”€ */
    .ticket-card {
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 16px;
        margin: 0 16px 12px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.02);
        transition: all 0.2s;
    }
    .ticket-card:active { 
        transform: scale(0.98); 
        border-color: #bae6fd; 
    }

    .ticket-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--border-color);
    }
    .ticket-title {
        color: var(--header-color);
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        flex: 1;
        min-width: 0;
        padding-right: 8px;
    }
    .ticket-title span {
        color: var(--text-muted);
        font-size: 11px;
    }
    .ticket-time {
        font-size: 10px;
        color: var(--text-muted);
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .ticket-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .ticket-badges {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }
    .badge {
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        display: inline-block;
    }
    .badge-open { background: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd; }
    .badge-answered { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .badge-replied { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
    .badge-closed { background: #f3f4f6; color: #6b7280; border: 1px solid #e5e7eb; }
    .badge-low { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
    .badge-medium { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .badge-high { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

    .view-btn {
        background: #f8fafc;
        border: 1px solid var(--border-color);
        padding: 6px 14px;
        border-radius: 6px;
        color: var(--header-color);
        font-size: 11px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.15s;
        white-space: nowrap;
    }
    .view-btn:active { background: #e0f2fe; border-color: #bae6fd; }

    /* â”€â”€â”€ EMPTY STATE â”€â”€â”€ */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: var(--card-bg);
        border-radius: 12px;
        border: 1px solid var(--border-color);
        margin: 0 16px;
    }
    .empty-state img {
        width: 80px;
        opacity: 0.3;
        margin-bottom: 16px;
    }
    .empty-state h5 {
        color: var(--text-muted);
        font-size: 14px;
        font-weight: 600;
    }

    /* â”€â”€â”€ PAGINATION â”€â”€â”€ */
    .pagination-wrapper {
        padding: 16px;
        text-align: center;
    }
    .pagination-wrapper .page-link {
        background: var(--card-bg);
        border-color: var(--border-color);
        color: var(--header-color);
        font-weight: 600;
    }
    .pagination-wrapper .active .page-link {
        background: var(--header-color);
        border-color: var(--header-color);
        color: #ffffff;
    }

    /* â”€â”€â”€ BOTTOM NAV â”€â”€â”€ */
    .bottom-nav-container {
        position: fixed;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100%;
        max-width: 480px;
        z-index: 10000;
        padding: 0 10px 8px 10px;
        background: #e8f0fa;
    }

    .bottom-nav {
        width: 100%;
        height: 58px;
        background: linear-gradient(180deg, #0e3d2c 0%, #0a2d1f 100%);
        border-radius: 999px;
        border: 1.5px solid #1a5c40;
        box-shadow:
            0 0 0 2px #e8f0fa,
            inset 0 1px 0 rgba(37,99,235,0.12),
            0 -2px 0 0 #2563eb,
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
        color: #3db88a;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.2px;
        padding: 6px 0;
        transition: color 0.2s;
        position: relative;
    }

    .nav-item.active { color: #f5c518; }
    .nav-item.active span { border-bottom: 2px solid #f5c518; padding-bottom: 1px; }

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
        background: linear-gradient(145deg, #2563eb, #123b66);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow:
            0 0 0 3px #e8f0fa,
            0 0 0 5px #2563eb,
            0 6px 20px rgba(37,99,235,0.45);
        font-size: 22px;
        color: #fff;
        margin-top: -18px;
        border: none;
    }

    @media (min-width: 900px) {
        .bottom-nav-container { max-width: 600px; }
    }
</style>

<div class="ticket-index-wrapper">

    <!-- HEADER -->
    <div class="header-bg">
        <a href="{{ route('user.home') }}"><i class="fas fa-chevron-left"></i></a>
        <h1>Support Tickets</h1>
        <a href="{{ route('ticket.open') }}" class="new-ticket-btn">
            <i class="fas fa-plus"></i> New
        </a>
    </div>

    <div style="padding-top: 16px;">

        @forelse($supports as $support)
            @php
                $statusClass = match($support->status) {
                    0 => 'badge-open',
                    1 => 'badge-answered',
                    2 => 'badge-replied',
                    3 => 'badge-closed',
                    default => 'badge-open'
                };
                $statusText = match($support->status) {
                    0 => 'Open',
                    1 => 'Answered',
                    2 => 'Replied',
                    3 => 'Closed',
                    default => 'Open'
                };
                $priorityClass = match($support->priority) {
                    1 => 'badge-low',
                    2 => 'badge-medium',
                    3 => 'badge-high',
                    default => 'badge-low'
                };
                $priorityText = match($support->priority) {
                    1 => 'Low',
                    2 => 'Medium',
                    3 => 'High',
                    default => 'Low'
                };
            @endphp

            <a href="{{ route('ticket.view', $support->ticket) }}" style="text-decoration: none; display: block;">
                <div class="ticket-card">
                    <div class="ticket-header">
                        <div class="ticket-title">
                            <span>[Ticket#{{ $support->ticket }}]</span> {{ __($support->subject) }}
                        </div>
                        <div class="ticket-time">
                            <i class="far fa-clock"></i> {{ diffForHumans($support->last_reply) }}
                        </div>
                    </div>
                    
                    <div class="ticket-footer">
                        <div class="ticket-badges">
                            <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                            <span class="badge {{ $priorityClass }}">{{ $priorityText }}</span>
                        </div>
                        <span class="view-btn">
                            <i class="fa fa-desktop"></i> View
                        </span>
                    </div>
                </div>
            </a>
        @empty
            <div class="empty-state">
                <img src="{{ asset('assets/images/empty_list.png') }}" alt="empty" onerror="this.style.display='none'">
                <i class="fas fa-ticket-alt" style="font-size: 48px; color: #d1d5db; display: block; margin-bottom: 16px;"></i>
                <h5>{{ __($emptyMessage ?? 'No tickets found') }}</h5>
            </div>
        @endforelse

        <!-- PAGINATION -->
        @if($supports->hasPages())
            <div class="pagination-wrapper">
                {{ paginateLinks($supports) }}
            </div>
        @endif

    </div>

</div>

@include($activeTemplate . 'partials.mobile_bottom_nav')

@endsection
