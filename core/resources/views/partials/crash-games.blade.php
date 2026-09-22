@php
$crashGames = [
    ["id"=>"a04d1f3eb8ccec8a4823bdf18e3f0e84","name"=>"Aviator","img"=>"https://spribe.co/assets/images/games/Av-new@2x.png?v=2.5.61"],
     ["id"=>"edef29b5eda8e2eaf721d7315491c51d","name"=>"Go Rush","img"=>"https://ossimg.91admin123admin.com/91club/gamelogo/JILI/224.png"],
];
@endphp

@foreach ($crashGames as $game)
    <div class="swiper-slide game-item-box game-card" data-category="crash">
        @auth
            <a href="{{ url('user/jili/launch?game_code='.$game['id']) }}" class="game-card-img">
        @else
            <a href="{{ route('user.login') }}" class="game-card-img">
        @endauth
                <img src="{{ $game['img'] }}" alt="{{ $game['name'] }}">
            </a>
    </div>
@endforeach