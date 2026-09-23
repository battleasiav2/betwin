@php
    $apkUrl = config('app.apk_url') ?: env('APK_DOWNLOAD_URL', asset('assets/apk/bet369win.apk'));
    $apkIcon = asset('assets/images/logo_icon/favicon.png');
    $apkName = __(gs('site_name')) . ' App Install';
@endphp

<div class="apk-banner" id="apkBanner" role="region" aria-label="@lang('App download')">
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

    <a class="apk-banner__btn" href="{{ $apkUrl }}" download="bet369win.apk" rel="noopener">
        @lang('DOWNLOAD')
    </a>
</div>

<script>
(function () {
    var key = 'b369_apk_banner_hidden';
    var banner = document.getElementById('apkBanner');
    var closeBtn = document.getElementById('apkBannerClose');
    if (!banner) return;
    try {
        if (localStorage.getItem(key) === '1') {
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
})();
</script>
