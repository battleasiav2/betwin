@php
    $apkUrl = config('app.apk_url') ?: env('APK_DOWNLOAD_URL', '');
    $apkIcon = asset('assets/images/logo_icon/favicon.png');
    $apkName = __(gs('site_name')) . ' App Install';
@endphp

<div class="apk-banner" id="apkBanner" role="region" aria-label="@lang('App install')">
    <button type="button" class="apk-banner__close" id="apkBannerClose" aria-label="@lang('Close')">
        <i class="fas fa-times"></i>
    </button>

    <img class="apk-banner__icon" src="{{ $apkIcon }}" alt="{{ __(gs('site_name')) }}" width="40" height="40">

    <div class="apk-banner__meta">
        <strong class="apk-banner__title">{{ $apkName }}</strong>
        <span class="apk-banner__stars" aria-label="4.5 stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
        </span>
    </div>

    <button type="button" class="apk-banner__btn" id="apkInstallBtn" data-apk="{{ $apkUrl }}">
        @lang('INSTALL')
    </button>
</div>

<script>
(function () {
    var key = 'b369_apk_banner_hidden';
    var banner = document.getElementById('apkBanner');
    var closeBtn = document.getElementById('apkBannerClose');
    var installBtn = document.getElementById('apkInstallBtn');
    if (!banner) return;

    var isStandalone = window.matchMedia('(display-mode: standalone)').matches
        || window.navigator.standalone === true;

    try {
        if (isStandalone || localStorage.getItem(key) === '1') {
            banner.classList.add('is-hidden');
            document.documentElement.classList.add('apk-banner-off');
            return;
        }
    } catch (e) {}

    if (closeBtn) {
        closeBtn.addEventListener('click', function () {
            banner.classList.add('is-hidden');
            document.documentElement.classList.add('apk-banner-off');
            try { localStorage.setItem(key, '1'); } catch (e) {}
        });
    }

    function toast(msg) {
        if (window.iziToast) {
            iziToast.info({ message: msg, position: 'topCenter', timeout: 3500 });
            return;
        }
        alert(msg);
    }

    async function installPwa() {
        var deferred = window.__b369PwaPrompt || null;
        if (deferred) {
            deferred.prompt();
            try {
                var choice = await deferred.userChoice;
                window.__b369PwaPrompt = null;
                if (choice && choice.outcome === 'accepted') {
                    banner.classList.add('is-hidden');
                    document.documentElement.classList.add('apk-banner-off');
                    try { localStorage.setItem(key, '1'); } catch (e) {}
                }
            } catch (e) {}
            return true;
        }
        return false;
    }

    function fallbackInstall() {
        var apk = installBtn && installBtn.getAttribute('data-apk');
        if (apk && apk.length > 4) {
            window.location.href = apk;
            return;
        }
        var ua = navigator.userAgent || '';
        var isIOS = /iPhone|iPad|iPod/i.test(ua);
        if (isIOS) {
            toast('@lang("Tap Share, then Add to Home Screen")');
        } else {
            toast('@lang("Open browser menu and tap Install app / Add to Home screen")');
        }
    }

    if (installBtn) {
        installBtn.addEventListener('click', async function (e) {
            e.preventDefault();
            var ok = await installPwa();
            if (!ok) fallbackInstall();
        });
    }

    window.__b369InstallApp = async function () {
        var ok = await installPwa();
        if (!ok) fallbackInstall();
    };

    window.addEventListener('beforeinstallprompt', function (e) {
        e.preventDefault();
        window.__b369PwaPrompt = e;
        banner.classList.remove('is-hidden');
        document.documentElement.classList.remove('apk-banner-off');
    });

    window.addEventListener('appinstalled', function () {
        banner.classList.add('is-hidden');
        document.documentElement.classList.add('apk-banner-off');
        try { localStorage.setItem(key, '1'); } catch (e) {}
        window.__b369PwaPrompt = null;
    });
})();
</script>
