{{-- Auto-refresh wallet balance after game win/bet (callback) without page reload --}}
@auth
<script>
(function () {
    var url = @json(route('user.live.balance'));
    var lastBalance = null;
    var timer = null;
    var inflight = false;
    var intervalMs = document.body && document.body.dataset.balancePollMs
        ? parseInt(document.body.dataset.balancePollMs, 10)
        : (document.body && document.body.classList.contains('is-game-play') ? 2500 : 4000);

    function applyBalance(data) {
        if (!data || data.code !== 0) return;
        var changed = lastBalance !== null && Number(lastBalance) !== Number(data.balance);
        lastBalance = data.balance;

        document.querySelectorAll('[data-live-balance], .js-live-balance').forEach(function (el) {
            var mode = el.getAttribute('data-live-balance') || 'full';
            if (mode === 'sym') {
                el.textContent = (data.cur_sym || '৳') + ' ' + data.balance_text;
            } else if (mode === 'raw') {
                el.textContent = data.balance_text;
            } else {
                el.textContent = data.balance_display;
            }
            if (changed) {
                el.classList.add('live-balance-flash');
                setTimeout(function () { el.classList.remove('live-balance-flash'); }, 700);
            }
        });

        if (changed && typeof window.onLiveBalanceChange === 'function') {
            window.onLiveBalanceChange(data);
        }
    }

    function fetchBalance(force) {
        if (inflight) return;
        if (!force && document.hidden) return;
        inflight = true;
        fetch(url, {
            method: 'GET',
            credentials: 'same-origin',
            cache: 'no-store',
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(function (r) { return r.ok ? r.json() : null; })
            .then(applyBalance)
            .catch(function () {})
            .finally(function () { inflight = false; });
    }

    function start(ms) {
        if (timer) clearInterval(timer);
        intervalMs = ms || intervalMs;
        fetchBalance(true);
        timer = setInterval(function () { fetchBalance(false); }, intervalMs);
    }

    document.addEventListener('visibilitychange', function () {
        if (!document.hidden) fetchBalance(true);
    });
    window.addEventListener('focus', function () { fetchBalance(true); });
    document.querySelectorAll('.js-live-balance-refresh').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            fetchBalance(true);
        });
    });

    window.Bet369LiveBalance = {
        refresh: function () { fetchBalance(true); },
        start: start
    };
    start(intervalMs);
})();
</script>
<style>
.js-live-balance.live-balance-flash {
    color: #16a34a !important;
    transition: color 0.2s ease;
}
</style>
@endauth
