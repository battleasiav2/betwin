@php
{{-- Same working RapidVerse sports gameCode; lobby codes like bti/sb fail. --}}
$hash = '92b24e4c25107367a80e0fe1a97c24e4';
$img = 'https://img.b6814jd.com/bjd/h5/assets/images/brand/white';
$sportsGames = [
    ['id' => $hash, 'name' => 'Lucky Sport', 'provider' => 'luckysport', 'img' => $img.'/provider-saba.png?v=1781595979466&source=mcdsrc'],
    ['id' => $hash, 'name' => 'BTI Sports', 'provider' => 'bti', 'img' => $img.'/provider-awcv2_bti.png?v=1781595979466&source=mcdsrc'],
    ['id' => $hash, 'name' => 'SABA Sports', 'provider' => 'saba', 'img' => $img.'/provider-saba.png?v=1781595979466&source=mcdsrc'],
    ['id' => $hash, 'name' => 'CMD368', 'provider' => 'cmd', 'img' => $img.'/provider-cmd368.png?v=1781595979466&source=mcdsrc'],
    ['id' => $hash, 'name' => '9 Wicket', 'provider' => '9wicket', 'img' => $img.'/provider-9wickets.png?v=1781595979466&source=mcdsrc'],
    ['id' => $hash, 'name' => 'United Gaming', 'provider' => 'ug', 'img' => $img.'/provider-ug.png?v=1781595979466&source=mcdsrc'],
    ['id' => $hash, 'name' => 'TF Gaming', 'provider' => 'tf', 'img' => $img.'/provider-tf.png?v=1781595979466&source=mcdsrc'],
    ['id' => $hash, 'name' => 'DP Sports', 'provider' => 'dpsports', 'img' => $img.'/provider-awcv2_sports.png?v=1781595979466&source=mcdsrc'],
    ['id' => $hash, 'name' => 'DP E-Sports', 'provider' => 'dpesports', 'img' => $img.'/provider-awcv2_esports.png?v=1781595979466&source=mcdsrc'],
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
