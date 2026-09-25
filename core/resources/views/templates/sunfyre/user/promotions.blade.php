@extends($activeTemplate . 'layouts.master')
@section('content')

@php
    $referralLink = route('user.register', ['ref' => auth()->user()->username ?? '']);
@endphp

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body { 
        background-color: #0b0f14; 
        color: #e8eef5; 
        font-family: 'Roboto', sans-serif; 
        padding-bottom: 90px;
        -webkit-tap-highlight-color: transparent;
    }

    .promotion-page-wrapper {
        min-height: 100vh;
    }

    /* ─── HEADER ─── */
    .header-premium { 
        background: linear-gradient(90deg, #0f1419 0%, #151b24 55%, #1a2330 100%); 
        border-bottom: 1px solid rgba(232,184,74,0.28); 
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 16px;
        height: 60px;
        position: sticky;
        top: 0;
        z-index: 50;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
    .header-premium-left {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .header-premium-left a {
        color: #ffffff;
        font-size: 18px;
        text-decoration: none;
    }
    .header-premium-left h1 {
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: #ffffff;
    }
    .header-premium-right a {
        color: #ffffff;
        font-size: 20px;
        text-decoration: none;
    }

    /* ─── TAB BUTTONS ─── */
    .tab-scroll {
        padding: 16px;
        overflow-x: auto;
        display: flex;
        gap: 10px;
        white-space: nowrap;
        background: #151b24;
        box-shadow: none;
        margin-bottom: 8px;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    .tab-scroll::-webkit-scrollbar { display: none; }

    .tab-btn {
        background-color: #151b24;
        color: #e8b84a;
        padding: 8px 20px;
        font-size: 11px;
        font-weight: 700;
        border-radius: 50px;
        white-space: nowrap;
        border: 1px solid rgba(255,255,255,0.08);
        box-shadow: none;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-block;
    }
    .tab-btn.active {
        background-color: #e8b84a;
        color: #0b0f14;
        border-color: #e8b84a;
    }
    .tab-btn:active { transform: scale(0.95); }

    /* ─── REFERRAL BOX ─── */
    .ref-box {
        margin: 16px;
        padding: 16px;
        background: rgba(45,212,168,0.1);
        border: 2px dashed rgba(232,184,74,0.4);
        border-radius: 12px;
    }
    .ref-box-label {
        font-size: 10px;
        color: #e8b84a;
        text-transform: uppercase;
        font-weight: 900;
        margin-bottom: 8px;
        letter-spacing: 0.5px;
    }
    .ref-input-group {
        display: flex;
        gap: 8px;
    }
    .ref-input-group input {
        flex: 1;
        background: #151b24;
        color: #e8b84a;
        padding: 10px 12px;
        border-radius: 8px;
        font-size: 11px;
        outline: none;
        border: 1px solid rgba(232,184,74,0.25);
    }
    .ref-copy-btn {
        background: linear-gradient(135deg, rgba(232,184,74,0.25), #151b24);
        color: #ffffff;
        padding: 10px 16px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        transition: all 0.15s;
        white-space: nowrap;
    }
    .ref-copy-btn:active { transform: scale(0.95); }

    /* ─── PROMO CARDS ─── */
    .promo-container {
        padding: 0 16px;
    }
    .promo-card {
        background: #151b24;
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 20px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.35);
    }
    .promo-card-img-wrap {
        position: relative;
    }
    .promo-card-img {
        width: 100%;
        height: 160px;
        object-fit: cover;
        display: block;
    }
    .promo-card-body {
        padding: 16px;
    }
    .promo-card-title {
        font-size: 14px;
        font-weight: 900;
        color: #e8b84a;
        text-transform: uppercase;
    }
    .promo-card-desc {
        font-size: 10px;
        color: #8b97a8;
        margin-top: 4px;
    }
    .promo-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1px solid rgba(255,255,255,0.08);
    }
    .promo-end-date {
        font-size: 9px;
        color: #8b97a8;
    }
    .promo-end-date i {
        margin-right: 4px;
    }
    .promo-details-btn {
        color: #e8b84a;
        font-size: 10px;
        font-weight: 700;
        text-decoration: underline;
        cursor: pointer;
        background: none;
        border: none;
    }
    .promo-details-content {
        display: none;
        margin-top: 12px;
        padding: 12px;
        background: #0f1419;
        border-radius: 8px;
        border: 1px solid rgba(255,255,255,0.08);
        font-size: 11px;
        color: #8b97a8;
        line-height: 1.6;
    }
    .promo-details-content.show {
        display: block;
    }
    .promo-bonus-tag {
        position: absolute;
        top: 12px;
        left: 0;
        background: linear-gradient(90deg, #ffc107, #ff9800);
        color: #000;
        padding: 4px 14px;
        border-radius: 0 15px 15px 0;
        font-weight: 800;
        font-size: 10px;
        z-index: 2;
    }

    /* ─── EMPTY STATE ─── */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #8b97a8;
    }
    .empty-state i {
        font-size: 48px;
        display: block;
        margin-bottom: 16px;
        opacity: 0.3;
    }
    .empty-state p {
        font-size: 14px;
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

    /* ─── TOAST ─── */
    .copy-toast {
        position: fixed;
        bottom: 100px;
        left: 50%;
        transform: translateX(-50%);
        background: linear-gradient(135deg, rgba(232,184,74,0.25), #151b24);
        color: #fff;
        padding: 10px 24px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 700;
        z-index: 99999;
        opacity: 0;
        transition: opacity 0.3s;
        pointer-events: none;
    }
    .copy-toast.show { opacity: 1; }

    @media (min-width: 900px) {
        .bottom-nav-container { max-width: 600px; }
    }
</style>

<div class="promotion-page-wrapper">

    <!-- HEADER -->
    <header class="header-premium">
        <div class="header-premium-left">
            <a href="{{ route('user.home') }}"><i class="fas fa-arrow-left"></i></a>
            <h1>Promotions</h1>
        </div>
        <div class="header-premium-right">
            <a href="{{ route('ticket.open') }}"><i class="fas fa-headset"></i></a>
        </div>
    </header>

    <!-- TAB BUTTONS -->
    <div class="tab-scroll">
        <a href="javascript:void(0)" class="tab-btn active" onclick="filterPromos('all', this)">ALL</a>
        <a href="javascript:void(0)" class="tab-btn" onclick="filterPromos('welcome', this)">WELCOME</a>
        <a href="javascript:void(0)" class="tab-btn" onclick="filterPromos('slots', this)">SLOTS</a>
    </div>

    <!-- REFERRAL BOX -->
    <div class="ref-box">
        <div class="ref-box-label">Your Referral Link (Earn Bonus)</div>
        <div class="ref-input-group">
            <input type="text" id="refLink" value="{{ $referralLink }}" readonly>
            <button onclick="copyRef()" class="ref-copy-btn">Copy</button>
        </div>
    </div>

    <!-- PROMOTIONS LIST -->
    <div class="promo-container" id="promoList">
        @forelse($promotions as $promotion)
            <div class="promo-card" data-category="{{ $promotion->category ?? 'all' }}">
                <div class="promo-card-img-wrap">
                    <img src="{{ getImage('assets/images/promotion/' . $promotion->image) }}" 
                         class="promo-card-img" 
                         onerror="this.src='https://placehold.co/600x250/1a5c92/FFF?text=PROMO'"
                         alt="{{ __($promotion->title) }}">
                    @if($promotion->bonus_percent)
                        <div class="promo-bonus-tag">{{ getAmount($promotion->bonus_percent) }}% BONUS</div>
                    @endif
                </div>
                <div class="promo-card-body">
                    <h3 class="promo-card-title">{{ __($promotion->title) }}</h3>
                    @if($promotion->short_details)
                        <p class="promo-card-desc">{{ __($promotion->short_details) }}</p>
                    @endif
                    <div class="promo-card-footer">
                        <span class="promo-end-date">
                            <i class="far fa-clock"></i> 
                            @if($promotion->end_date)
                                Ends: {{ showDateTime($promotion->end_date, 'd/m/Y') }}
                            @else
                                Ongoing
                            @endif
                        </span>
                        @if($promotion->details)
                            <button onclick="toggleDetails({{ $promotion->id }})" class="promo-details-btn">
                                Show Details
                            </button>
                        @else
                            <a href="{{ route('user.promotion.details', $promotion->id) }}" class="promo-details-btn" style="color: #e8b84a; font-size: 10px; font-weight: 700; text-decoration: underline;">
                                View Details
                            </a>
                        @endif
                    </div>
                    @if($promotion->details)
                        <div id="details-{{ $promotion->id }}" class="promo-details-content">
                            {{ __($promotion->details) }}
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-gift"></i>
                <p>No active promotions available.</p>
            </div>
        @endforelse
    </div>

</div>

<!-- COPY TOAST -->
<div class="copy-toast" id="copyToast">Referral link copied!</div>

<!-- BOTTOM NAVIGATION -->
<div class="bottom-nav-container">
    <div class="bottom-nav">
        <a href="{{ route('user.home') }}" class="nav-item">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('user.promotions') }}" class="nav-item active">
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
    function toggleDetails(id) {
        const el = document.getElementById('details-' + id);
        if (el) {
            el.classList.toggle('show');
        }
    }

    function copyRef() {
        const copyText = document.getElementById("refLink");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value).then(() => {
            const toast = document.getElementById('copyToast');
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 2000);
        });
    }

    function filterPromos(category, btn) {
        document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
        btn.classList.add('active');

        const cards = document.querySelectorAll('.promo-card');
        cards.forEach(card => {
            if (category === 'all' || card.getAttribute('data-category') === category) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }
</script>

@endsection
