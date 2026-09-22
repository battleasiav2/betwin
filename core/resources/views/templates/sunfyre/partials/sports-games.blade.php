@php
$sportsGames = [
    ["id"=>"92b24e4c25107367a80e0fe1a97c24e4","name"=>"LuckSportGaming","img"=>"https://framerusercontent.com/images/qhDfstpGxbf3Aiidtc4nIk8OdKU.png?width=1000&height=1000"],
];
@endphp

@foreach ($sportsGames as $game)
    <div class="swiper-slide game-item-box game-card" data-category="sports">
        @auth
            <a href="{{ url('user/jili/launch?game_code='.$game['id']) }}" class="game-card-img">
        @else
            <a href="{{ route('user.login') }}" class="game-card-img">
        @endauth
                <img src="{{ $game['img'] }}" alt="{{ $game['name'] }}">
            </a>
    </div>
@endforeach