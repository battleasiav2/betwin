@php
// Same working RapidVerse sports gameCode; lobby codes like bti/sb fail.
$hash = '92b24e4c25107367a80e0fe1a97c24e4';
$icon = asset('assets/images/sports');
$sportsGames = [
    ['id' => $hash, 'name' => 'Lucky Sport', 'provider' => 'luckysport', 'img' => $icon.'/lucky.svg'],
    ['id' => $hash, 'name' => 'BTI Sports', 'provider' => 'bti', 'img' => $icon.'/bti.svg'],
    ['id' => $hash, 'name' => 'SABA Sports', 'provider' => 'saba', 'img' => $icon.'/saba.svg'],
    ['id' => $hash, 'name' => 'CMD368', 'provider' => 'cmd', 'img' => $icon.'/cmd.svg'],
    ['id' => $hash, 'name' => '9 Wicket', 'provider' => '9wicket', 'img' => $icon.'/9wicket.svg'],
    ['id' => $hash, 'name' => 'United Gaming', 'provider' => 'ug', 'img' => $icon.'/ug.svg'],
    ['id' => $hash, 'name' => 'TF Gaming', 'provider' => 'tf', 'img' => $icon.'/tf.svg'],
    ['id' => $hash, 'name' => 'DP Sports', 'provider' => 'dpsports', 'img' => $icon.'/dpsports.svg'],
    ['id' => $hash, 'name' => 'DP E-Sports', 'provider' => 'dpesports', 'img' => $icon.'/dpesports.svg'],
];
@endphp

@foreach ($sportsGames as $game)
    <div class="swiper-slide game-item-box game-card" data-category="sports">
        @auth
            <a href="{{ url('user/jili/launch?game_code='.$game['id'].'&provider='.($game['provider'] ?? 'luckysport')) }}" class="game-card-img" title="{{ $game['name'] }}">
        @else
            <a href="{{ route('user.login') }}" class="game-card-img" title="{{ $game['name'] }}">
        @endauth
                <img src="{{ $game['img'] }}" alt="{{ $game['name'] }}" loading="lazy">
            </a>
        <div class="game-card-name" style="font-size:11px;font-weight:700;text-align:center;padding:4px 2px 6px;color:#123b66;">{{ $game['name'] }}</div>
    </div>
@endforeach
