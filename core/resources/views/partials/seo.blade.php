@php
    if (isset($seoContents) && $seoContents) {
        $seoContents = (object) $seoContents;
        $socialImageSize = explode('x', getFileSize('seo'));
    } elseif (!empty($seo)) {
        $seoContents = $seo;
        $socialImageSize = explode('x', getFileSize('seo'));
        $seoContents->image = getImage(getFilePath('seo') . '/' . $seo->image);
    } else {
        $seoContents = null;
    }

    $ogImagePath = $seoImage ?? (isset($seo) && !empty($seo->image) ? (getImage(getFilePath('seo') . '/' . $seo->image)) : null);
    $ogImageExt = is_string($ogImagePath) ? pathinfo(parse_url($ogImagePath, PHP_URL_PATH) ?: $ogImagePath, PATHINFO_EXTENSION) : '';
    if (!$ogImageExt) {
        $ogImageExt = 'png';
    }
@endphp

<meta name="title" Content="{{ gs()->sitename(__($pageTitle)) }}">

@if ($seoContents)
    <meta name="description" content="{{ $seoContents->meta_description ?? ($seoContents->description ?? '') }}">
    @php
        $seoKeywords = $seoContents->keywords ?? [];
        if (is_string($seoKeywords)) {
            $seoKeywords = array_filter(array_map('trim', explode(',', $seoKeywords)));
        }
        if (!is_array($seoKeywords)) {
            $seoKeywords = [];
        }
    @endphp
    <meta name="keywords" content="{{ implode(',', $seoKeywords) }}">
    <link rel="shortcut icon" href="{{ siteFavicon() }}" type="image/x-icon">
    <link rel="canonical" href="{{ url()->current() }}" />

    @if ((isset($seoContents->meta_robots) && $seoContents->meta_robots) || (isset($seo->meta_robots) && $seo->meta_robots))
        <meta name="robots" content="{{ isset($seoContents->meta_robots) && $seoContents->meta_robots ? $seoContents->meta_robots : $seo->meta_robots }}" />
    @endif

    {{-- <!-- Apple Stuff --> --}}
    <link rel="apple-touch-icon" href="{{ asset('assets/images/logo_icon/pwa_favicon.png') }}">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="{{ gs()->sitename($pageTitle) }}">
    {{-- <!-- Google / Search Engine Tags --> --}}
    <meta itemprop="name" content="{{ gs()->sitename($pageTitle) }}">
    <meta itemprop="description" content="{{ $seoContents->description ?? '' }}">
    <meta itemprop="image" content="{{ $seoContents->image ?? '' }}">
    {{-- <!-- Facebook Meta Tags --> --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $seoContents->social_title ?? gs()->sitename($pageTitle) }}">
    <meta property="og:description" content="{{ $seoContents->social_description ?? ($seoContents->description ?? '') }}">
    <meta property="og:image" content="{{ $seoContents->image ?? '' }}" />
    <meta property="og:image:type" content="image/{{ $ogImageExt }}">
    <meta property="og:image:width" content="{{ $socialImageSize[0] ?? 1200 }}" />
    <meta property="og:image:height" content="{{ $socialImageSize[1] ?? 630 }}" />
    <meta property="og:url" content="{{ url()->current() }}">
    {{-- <!-- Twitter Meta Tags --> --}}
    <meta name="twitter:card" content="summary_large_image">
@endif
