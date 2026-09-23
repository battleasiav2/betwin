@php
$sportsGames = [
    [
        "id" => "92b24e4c25107367a80e0fe1a97c24e4",
        "name" => "Lucky Sport",
        "provider" => "luckysport",
        "img" => "https://img.b6814jd.com/bjd/h5/assets/images/brand/white/provider-saba.png?v=1781595979466&source=mcdsrc",
    ],
    [
        "id" => "bti",
        "name" => "BTI Sports",
        "provider" => "bti",
        "img" => "https://img.b6814jd.com/bjd/h5/assets/images/brand/white/provider-awcv2_bti.png?v=1781595979466&source=mcdsrc",
    ],
    [
        "id" => "sb",
        "name" => "SABA Sports",
        "provider" => "saba",
        "img" => "https://img.b6814jd.com/bjd/h5/assets/images/brand/white/provider-saba.png?v=1781595979466&source=mcdsrc",
    ],
    [
        "id" => "cmd",
        "name" => "CMD Sports",
        "provider" => "cmd",
        "img" => "https://img.b6814jd.com/bjd/h5/assets/images/brand/white/provider-cmd368.png?v=1781595979466&source=mcdsrc",
    ],
    [
        "id" => "9w",
        "name" => "9 Wicket",
        "provider" => "9wicket",
        "img" => "https://img.b6814jd.com/bjd/h5/assets/images/brand/white/provider-awcv2_9wickets.png?v=1781595979466&source=mcdsrc",
    ],
    [
        "id" => "ug",
        "name" => "United Gaming",
        "provider" => "ug",
        "img" => "https://img.b6814jd.com/bjd/h5/assets/images/brand/white/provider-awcv2_ug.png?v=1781595979466&source=mcdsrc",
    ],
    [
        "id" => "tf",
        "name" => "TF Gaming",
        "provider" => "tf",
        "img" => "https://img.b6814jd.com/bjd/h5/assets/images/brand/white/provider-awcv2_tfgaming.png?v=1781595979466&source=mcdsrc",
    ],
    [
        "id" => "dps",
        "name" => "DP Sports",
        "provider" => "dpsports",
        "img" => "https://img.b6814jd.com/bjd/h5/assets/images/brand/white/provider-awcv2_im.png?v=1781595979466&source=mcdsrc",
    ],
    [
        "id" => "esport",
        "name" => "E-Sports",
        "provider" => "dpesports",
        "img" => "https://img.b6814jd.com/bjd/h5/assets/images/brand/white/provider-awcv2_esports.png?v=1781595979466&source=mcdsrc",
    ],
];
@endphp

@foreach ($sportsGames as $game)
    <div class="swiper-slide game-item-box game-card" data-category="sports">
        @auth
            <a href="{{ url('user/jili/launch?game_code='.$game['id'].'&provider='.($game['provider'] ?? 'bti')) }}" class="game-card-img" title="{{ $game['name'] }}">
        @else
            <a href="{{ route('user.login') }}" class="game-card-img" title="{{ $game['name'] }}">
        @endauth
                <img src="{{ $game['img'] }}" alt="{{ $game['name'] }}" loading="lazy"
                     onerror="this.src='https://img.b6814jd.com/bjd/h5/assets/images/brand/white/provider-saba.png?v=1781595979466&source=mcdsrc'">
            </a>
        <div class="game-card-name" style="font-size:11px;font-weight:700;text-align:center;padding:4px 2px 6px;color:#123b66;">{{ $game['name'] }}</div>
    </div>
@endforeach
