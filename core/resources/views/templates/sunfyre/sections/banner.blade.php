@php
    $bannerContent = getContent('banner.content', true);
@endphp

<style>
    .banner-section {
        padding: 0;
        margin-top: 45px;
        background-color: #0b0f14 !important;
        width: 100%;
        overflow: hidden;
    }

    .banner-wrapper {
        width: 100%;
        padding: 0;
        background-color: #0b0f14 !important;
    }

    .banner-image-container {
        width: 100%;
        display: block;
        background-color: #0b0f14 !important;
    }

    .banner-image-container img {
        width: 100%;
        height: auto;
        display: block;
        border: none !important;
        outline: none !important;
        box-shadow: none !important;
        border-radius: 0 !important;
    }

    @media (max-width: 767px) {
        .banner-section {
            margin-top: 40px;
        }
    }
</style>

<section class="banner-section">
    <div class="banner-wrapper">
        <div class="banner-image-container">
            <img src="{{ getImage('assets/images/frontend/banner/' . @$bannerContent->data_values->image, '670x675') }}" alt="banner">
        </div>
    </div>
</section>