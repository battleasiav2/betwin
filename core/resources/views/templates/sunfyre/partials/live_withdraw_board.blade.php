@php
    $liveItems = \App\Lib\LiveWithdrawFeed::items(18);
    $curText = gs('cur_text') ?: 'BDT';
@endphp

<section class="live-withdraw-board" id="liveWithdrawBoard" aria-label="@lang('Live withdrawals')">
    <div class="lwb-head">
        <div class="lwb-head__left">
            <span class="lwb-live-dot" aria-hidden="true"></span>
            <h3 class="lwb-title">@lang('Live Withdrawals')</h3>
            <span class="lwb-chip">@lang('Running')</span>
        </div>
        <div class="lwb-head__right">
            <i class="fas fa-trophy lwb-trophy"></i>
            <span>@lang('Real winners')</span>
        </div>
    </div>

    <div class="lwb-viewport">
        <ul class="lwb-list" id="liveWithdrawList">
            @foreach ($liveItems as $item)
                <li class="lwb-row">
                    <div class="lwb-user">
                        <img class="lwb-avatar" src="{{ $item['avatar'] }}" alt="" loading="lazy" referrerpolicy="no-referrer"
                             onerror="this.onerror=null;this.src='{{ asset('assets/images/default.png') }}';">
                        <div class="lwb-meta">
                            <strong class="lwb-mobile">{{ $item['mobile'] }}</strong>
                            <span class="lwb-when">{{ $item['when'] }}</span>
                        </div>
                    </div>
                    <div class="lwb-win">
                        <span class="lwb-badge" title="@lang('Successful withdraw')">
                            <i class="fas fa-crown"></i>
                        </span>
                        <div class="lwb-amount-wrap">
                            <span class="lwb-amount">+{{ $item['amount_f'] }}</span>
                            <span class="lwb-ok"><i class="fas fa-check-circle"></i> @lang('Paid')</span>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</section>

<style>
.live-withdraw-board {
    margin: 0 0 8px;
    padding: 12px 12px 8px;
    background: linear-gradient(180deg, #121821 0%, #0b0f14 100%);
    border: 1px solid rgba(232,184,74,0.22);
    border-radius: 16px;
    box-shadow: 0 10px 28px rgba(0,0,0,0.35);
    overflow: hidden;
}
.lwb-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    margin-bottom: 10px;
}
.lwb-head__left, .lwb-head__right {
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.lwb-title {
    margin: 0;
    color: #f4f7fb;
    font-size: 15px;
    font-weight: 800;
    letter-spacing: 0.2px;
}
.lwb-live-dot {
    width: 9px;
    height: 9px;
    border-radius: 50%;
    background: #22c55e;
    box-shadow: 0 0 0 0 rgba(34,197,94,0.55);
    animation: lwbPulse 1.6s infinite;
}
@keyframes lwbPulse {
    0% { box-shadow: 0 0 0 0 rgba(34,197,94,0.55); }
    70% { box-shadow: 0 0 0 8px rgba(34,197,94,0); }
    100% { box-shadow: 0 0 0 0 rgba(34,197,94,0); }
}
.lwb-chip {
    font-size: 10px;
    font-weight: 800;
    color: #86efac;
    background: rgba(34,197,94,0.14);
    border: 1px solid rgba(34,197,94,0.28);
    border-radius: 999px;
    padding: 2px 8px;
    text-transform: uppercase;
}
.lwb-head__right {
    color: #e8b84a;
    font-size: 11px;
    font-weight: 700;
}
.lwb-trophy { color: #e8b84a; font-size: 13px; }
.lwb-viewport {
    height: 280px;
    overflow: hidden;
    position: relative;
    mask-image: linear-gradient(180deg, transparent 0%, #000 8%, #000 92%, transparent 100%);
    -webkit-mask-image: linear-gradient(180deg, transparent 0%, #000 8%, #000 92%, transparent 100%);
}
.lwb-list {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 8px;
    animation: lwbScroll 42s linear infinite;
}
.lwb-list:hover { animation-play-state: paused; }
@keyframes lwbScroll {
    0% { transform: translateY(0); }
    100% { transform: translateY(-50%); }
}
.lwb-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    padding: 10px 12px;
    border-radius: 12px;
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.06);
}
.lwb-row.is-new {
    animation: lwbFlash 1.1s ease;
    border-color: rgba(232,184,74,0.45);
}
@keyframes lwbFlash {
    0% { background: rgba(232,184,74,0.18); }
    100% { background: rgba(255,255,255,0.03); }
}
.lwb-user {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 0;
}
.lwb-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid rgba(232,184,74,0.45);
    background: #0f172a;
    flex-shrink: 0;
}
.lwb-meta {
    display: flex;
    flex-direction: column;
    gap: 2px;
    min-width: 0;
}
.lwb-mobile {
    color: #f8fafc;
    font-size: 13px;
    font-weight: 800;
    letter-spacing: 0.3px;
}
.lwb-when {
    color: #94a3b8;
    font-size: 11px;
}
.lwb-win {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}
.lwb-badge {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(145deg, #f6d365 0%, #e8b84a 45%, #b8871d 100%);
    color: #3b2a05;
    box-shadow: 0 4px 10px rgba(232,184,74,0.35);
    font-size: 12px;
}
.lwb-amount-wrap {
    text-align: right;
}
.lwb-amount {
    display: block;
    color: #4ade80;
    font-size: 13px;
    font-weight: 900;
    line-height: 1.15;
}
.lwb-ok {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    color: #86efac;
    font-size: 10px;
    font-weight: 700;
}
@media (max-width: 480px) {
    .lwb-viewport { height: 250px; }
    .lwb-mobile { font-size: 12px; }
    .lwb-amount { font-size: 12px; }
    .lwb-head__right span { display: none; }
}
</style>

<script>
(function () {
    var list = document.getElementById('liveWithdrawList');
    if (!list) return;

    // Duplicate rows for seamless loop
    list.innerHTML = list.innerHTML + list.innerHTML;

    var endpoint = @json(route('live.withdrawals'));
    var defaultAvatar = @json(asset('assets/images/default.png'));

    function rowHtml(item) {
        var avatar = item.avatar || defaultAvatar;
        var when = item.when || 'just now';
        var mobile = item.mobile || '01*******';
        var amount = item.amount_f || item.amount || '';
        return '' +
            '<li class="lwb-row is-new">' +
            '  <div class="lwb-user">' +
            '    <img class="lwb-avatar" src="' + avatar + '" alt="" loading="lazy" referrerpolicy="no-referrer" onerror="this.onerror=null;this.src=\'' + defaultAvatar + '\';">' +
            '    <div class="lwb-meta">' +
            '      <strong class="lwb-mobile">' + mobile + '</strong>' +
            '      <span class="lwb-when">' + when + '</span>' +
            '    </div>' +
            '  </div>' +
            '  <div class="lwb-win">' +
            '    <span class="lwb-badge"><i class="fas fa-crown"></i></span>' +
            '    <div class="lwb-amount-wrap">' +
            '      <span class="lwb-amount">+' + amount + '</span>' +
            '      <span class="lwb-ok"><i class="fas fa-check-circle"></i> Paid</span>' +
            '    </div>' +
            '  </div>' +
            '</li>';
    }

    function refresh() {
        fetch(endpoint, { headers: { 'Accept': 'application/json' }, cache: 'no-store' })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (!data || !data.items || !data.items.length) return;
                var half = Math.ceil(list.children.length / 2) || 1;
                var html = data.items.map(rowHtml).join('');
                list.innerHTML = html + html;
            })
            .catch(function () {});
    }

    // Live refresh every 25s so board keeps changing
    setInterval(refresh, 25000);
})();
</script>
