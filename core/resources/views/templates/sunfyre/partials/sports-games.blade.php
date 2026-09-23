@php
// RapidVerse: only this sports gameCode launches. Fake codes (bti/sb/cmd) return Game not found.
$sportsGames = [
    [
        "id" => "92b24e4c25107367a80e0fe1a97c24e4",
        "name" => "Lucky Sport",
        "provider" => "luckysport",
        "img" => "https://img.b6814jd.com/bjd/h5/assets/images/brand/white/provider-saba.png?v=1781595979466&source=mcdsrc",
    ],
];
@endphp

@foreach ($sportsGames as $game)
    <div class="swiper-slide game-item-box game-card" data-category="sports">
        @auth
            <a href="{{ url('user/jili/launch?game_code='.$game['id'].'&provider='.($game['provider'] ?? 'luckysport')) }}" class="game-card-img" title="{{ $game['name'] }}">
        @else
            <a href="{{ route('user.login') }}" class="game-card-img" title="{{ $game['name'] }}">
        @endauth
                <img src="{{ $game['img'] }}" alt="{{ $game['name'] }}" loading="lazy" referrerpolicy="no-referrer"
                     onerror="this.src='https://img.b6814jd.com/bjd/h5/assets/images/brand/white/provider-saba.png?v=1781595979466&source=mcdsrc'">
            </a>
        <div class="game-card-name" style="font-size:11px;font-weight:700;text-align:center;padding:4px 2px 6px;color:#123b66;">{{ $game['name'] }}</div>
    </div>
@endforeach
