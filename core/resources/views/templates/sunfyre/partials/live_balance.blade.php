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
        : 2000;

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

    function fetchBalance() {
        if (inflight || document.hidden) return;
        inflight = true;
        fetch(url, {
            method: 'GET',
            credentials: 'same-origin',
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
        fetchBalance();
        timer = setInterval(fetchBalance, intervalMs);
    }

    document.addEventListener('visibilitychange', function () {
        if (!document.hidden) fetchBalance();
    });
    window.addEventListener('focus', fetchBalance);
    document.querySelectorAll('.js-live-balance-refresh').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            fetchBalance();
        });
    });

    window.Bet369LiveBalance = { refresh: fetchBalance, start: start };
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
