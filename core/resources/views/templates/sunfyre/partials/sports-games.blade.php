@php
// Unique RapidVerse sports lobby codes (each vendor has its own hash).
$sportsGames = [
    [
        'id' => '92b24e4c25107367a80e0fe1a97c24e4',
        'name' => 'Lucky Sport',
        'provider' => 'luckysport',
        'img' => asset('assets/images/sports/lucky.webp'),
        'tile' => 'dark',
    ],
    [
        'id' => '08ced9dd788aed11ff3c7f387ae0f063',
        'name' => 'SABA Sports',
        'provider' => 'sabasports',
        'img' => asset('assets/images/sports/saba.jpg'),
        'tile' => 'light',
    ],
    [
        'id' => '4d31f1186a81e208c003a7e37411ce35',
        'name' => 'BTI Sports',
        'provider' => 'bti',
        'img' => asset('assets/images/sports/bti.webp'),
        'tile' => 'light',
    ],
    [
        'id' => '1f7fbf84bf1bcc08c3a7ea27db75f366',
        'name' => 'CMD Sports',
        'provider' => 'cmd',
        'img' => asset('assets/images/sports/cmd.png'),
        'tile' => 'dark',
    ],
    [
        'id' => 'c4b2813f6bbc5abf502ddfb857e604eb',
        'name' => 'United Gaming',
        'provider' => 'ug',
        'img' => asset('assets/images/sports/ug.webp'),
        'tile' => 'light',
    ],
    [
        'id' => '341827d4370bb198b18364e2d75e6916',
        'name' => 'SBO',
        'provider' => 'sbo',
        'img' => asset('assets/images/sports/sbo.png'),
        'tile' => 'light',
    ],
    [
        'id' => '4ee8e0051a035b463b47c3c473ce317d',
        'name' => 'TF Sports',
        'provider' => 'tf',
        'img' => asset('assets/images/sports/tf.webp'),
        'tile' => 'light',
    ],
    [
        'id' => '23c2dca76f87d7b7f239833060c8751e',
        'name' => 'DP Sports',
        'provider' => 'dpsports',
        'img' => asset('assets/images/sports/dpsports.png'),
        'tile' => 'dark',
    ],
    [
        'id' => 'e130116fdc9bcde2dbb31735b6c365d6',
        'name' => 'DP Esports',
        'provider' => 'dpesports',
        'img' => asset('assets/images/sports/dpesports.jpg'),
        'tile' => 'dark',
    ],
];

$fallbackSvg = function (string $label, string $bg1 = '#0f766e', string $bg2 = '#123028') {
    $t = strtoupper(mb_substr(preg_replace('/[^A-Za-z0-9]/', '', $label), 0, 3) ?: 'SP');
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><defs><linearGradient id="g" x1="0" y1="0" x2="1" y2="1"><stop offset="0%" stop-color="'.$bg1.'"/><stop offset="100%" stop-color="'.$bg2.'"/></linearGradient></defs><rect width="200" height="200" fill="url(#g)"/><text x="100" y="112" text-anchor="middle" font-family="Arial Black,Arial,sans-serif" font-size="42" font-weight="900" fill="#e8b84a">'.$t.'</text></svg>';
    return 'data:image/svg+xml;base64,' . base64_encode($svg);
};
@endphp

@foreach ($sportsGames as $game)
    @php
        $img = !empty($game['img']) ? $game['img'] : $fallbackSvg($game['name']);
        $prov = $game['provider'] ?? 'luckysport';
        $tile = $game['tile'] ?? 'dark';
    @endphp
    <div class="swiper-slide game-item-box game-card sports-provider-card" data-category="sports" data-provider="{{ $prov }}" data-game-id="{{ $game['id'] }}" data-game-name="{{ $game['name'] }}" data-game-img="{{ $img }}">
        @auth
            <a href="{{ url('user/jili/launch?game_code='.$game['id'].'&provider='.$prov) }}" class="game-card-img sports-logo-tile sports-logo-tile--{{ $tile }}" title="{{ $game['name'] }}">
        @else
            <a href="{{ route('user.login') }}" class="game-card-img sports-logo-tile sports-logo-tile--{{ $tile }}" title="{{ $game['name'] }}">
        @endauth
                <img src="{{ $img }}" alt="{{ $game['name'] }}" loading="lazy" decoding="async"
                     onerror="this.onerror=null;this.src='{{ $fallbackSvg($game['name']) }}';">
            </a>
        <div class="game-card-name">{{ $game['name'] }}</div>
    </div>
@endforeach
