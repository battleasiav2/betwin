@php
// RapidVerse ignores vendorCode and resolves by gameCode alone, so every code here was
// launch-tested to open the intended provider game (numeric JILI/FC codes can collide).
$categoryGames = [
    'arcade' => [
        ['id' => '27001', 'name' => 'Money Tree Dozer', 'provider' => 'fc', 'img' => 'fc-27001.webp'],
        ['id' => '242', 'name' => 'Plinko', 'provider' => 'jili', 'img' => 'jili-242.png'],
        ['id' => '3239c0725fa13cdc7ee3107090698131', 'name' => 'Dice', 'provider' => 'km', 'img' => 'km-bonus-dice.webp'],
        ['id' => '27005', 'name' => 'Lightning Bomb', 'provider' => 'fc', 'img' => 'fc-27005.webp'],
    ],
    'table' => [
        ['id' => '124', 'name' => '7Up 7Down', 'provider' => 'jili', 'img' => 'jili-124.png'],
        ['id' => '125', 'name' => 'Sic Bo', 'provider' => 'jili', 'img' => 'jili-125.png'],
        ['id' => '72ce7e04ce95ee94eef172c0dfd6dc17', 'name' => 'Mines', 'provider' => 'jili', 'img' => 'jili-229.png'],
        ['id' => '79', 'name' => 'Andar Bahar', 'provider' => 'jili', 'img' => 'jili-79.png'],
        ['id' => '123', 'name' => 'Dragon & Tiger', 'provider' => 'jili', 'img' => 'jili-123.png'],
        ['id' => '690', 'name' => 'Chicken Dash', 'provider' => 'jili', 'img' => 'jili-690.png'],
        ['id' => '200', 'name' => 'Pappu', 'provider' => 'jili', 'img' => 'jili-200.png'],
        ['id' => '94', 'name' => 'Rummy', 'provider' => 'jili', 'img' => 'jili-94.png'],
    ],
    'slots' => [
        ['id' => 'a04d1f3eb8ccec8a4823bdf18e3f0e84', 'name' => 'Aviator', 'provider' => 'spribe', 'img' => 'spribe-aviator.webp'],
        ['id' => 'bdfb23c974a2517198c5443adeea77a8', 'name' => 'Super Ace', 'provider' => 'jili', 'img' => 'jili-49.png'],
    ],
];
$games = $categoryGames[$cat ?? ''] ?? [];
@endphp

@foreach ($games as $game)
    @php $img = asset('assets/images/games/cat/' . $game['img']); @endphp
    <div class="game-item-box game-card cat-tile-card" data-category="{{ $cat }}" data-provider="{{ $game['provider'] }}" data-game-id="{{ $game['id'] }}" data-game-name="{{ $game['name'] }}" data-game-img="{{ $img }}">
        @auth
            <a href="{{ url('user/jili/launch?game_code='.$game['id'].'&provider='.$game['provider']) }}" class="game-card-img" title="{{ $game['name'] }}">
        @else
            <a href="{{ route('user.login') }}" class="game-card-img" title="{{ $game['name'] }}">
        @endauth
                <img src="{{ $img }}" alt="{{ $game['name'] }}" loading="lazy" decoding="async">
            </a>
    </div>
@endforeach
