@php
    $card365Games = [
        ["id" => "707", "name" => "3 Patti Classic", "img" => "https://ossimg.91admin123admin.com/91club/gamelogo/Card365/707_20250210142254071.png"],
        ["id" => "710", "name" => "Rummy", "img" => "https://ossimg.91admin123admin.com/91club/gamelogo/Card365/710_20250210142312293.png"],
        ["id" => "563", "name" => "Three Pictures", "img" => "https://ossimg.91admin123admin.com/91club/gamelogo/Card365/563_20250210142918946.png"],
        ["id" => "921", "name" => "Andar Bahar", "img" => "https://ossimg.91admin123admin.com/91club/gamelogo/Card365/921_20250210142530573.png"],
        ["id" => "561", "name" => "3 Cards", "img" => "https://ossimg.91admin123admin.com/91club/gamelogo/Card365/561_20250210142727041.png"],
        ["id" => "557", "name" => "Battle Blackjack", "img" => "https://ossimg.91admin123admin.com/91club/gamelogo/Card365/557_20250210142657758.png"],
        ["id" => "701", "name" => "365 Poker", "img" => "https://ossimg.91admin123admin.com/91club/gamelogo/Card365/701_20250210143113012.png"],
        ["id" => "918", "name" => "Jhandi Munda", "img" => "https://ossimg.91admin123admin.com/91club/gamelogo/Card365/918_20250210142518389.png"],
        ["id" => "556", "name" => "Star Ox", "img" => "https://ossimg.91admin123admin.com/91club/gamelogo/Card365/556_20250210142642746.png"],
        ["id" => "562", "name" => "13 Cards", "img" => "https://ossimg.91admin123admin.com/91club/gamelogo/Card365/562_20250210142741048.png"],
        ["id" => "789", "name" => "Pok Deng", "img" => "https://ossimg.91admin123admin.com/91club/gamelogo/Card365/789_20250210142850015.png"],
        ["id" => "565", "name" => "Texas Poker", "img" => "https://ossimg.91admin123admin.com/91club/gamelogo/Card365/565_20250210142946541.png"],
        ["id" => "706", "name" => "3 Pictures Special", "img" => "https://ossimg.91admin123admin.com/91club/gamelogo/Card365/706_20250210143217673.png"],
        ["id" => "705", "name" => "Casino Ox", "img" => "https://ossimg.91admin123admin.com/91club/gamelogo/Card365/705_20250210143201613.png"],
        ["id" => "566", "name" => "Casino 3 Pictures", "img" => "https://ossimg.91admin123admin.com/91club/gamelogo/Card365/566_20250210142959829.png"],
        ["id" => "559", "name" => "Run Fast", "img" => "https://ossimg.91admin123admin.com/91club/gamelogo/Card365/559_20250210142713153.png"],
        ["id" => "917", "name" => "Thai Hi-Lo", "img" => "https://ossimg.91admin123admin.com/91club/gamelogo/Card365/917_20250210142506257.png"],
        ["id" => "570", "name" => "2-plyr Fight Landlord", "img" => "https://ossimg.91admin123admin.com/91club/gamelogo/Card365/570_20250210143044153.png"],
        ["id" => "555", "name" => "Ox Banker", "img" => "https://ossimg.91admin123admin.com/91club/gamelogo/Card365/555_20250210142630027.png"],
        ["id" => "702", "name" => "8 Cards", "img" => "https://ossimg.91admin123admin.com/91club/gamelogo/Card365/702_20250210143127828.png"],
        ["id" => "666", "name" => "Happy Ox", "img" => "https://ossimg.91admin123admin.com/91club/gamelogo/Card365/666_20250210143058261.png"],
        ["id" => "449", "name" => "2-plyr Mahjong", "img" => "https://ossimg.91admin123admin.com/91club/gamelogo/Card365/449_20250210142553312.png"],
        ["id" => "913", "name" => "Thai Fish Prawn Crab", "img" => "https://ossimg.91admin123admin.com/91club/gamelogo/Card365/913_20250210142438936.png"],
        ["id" => "452", "name" => "Guangdong Mahjong", "img" => "https://ossimg.91admin123admin.com/91club/gamelogo/Card365/452_20250210142605729.png"],
        ["id" => "445", "name" => "Red Mahjong", "img" => "https://ossimg.91admin123admin.com/91club/gamelogo/Card365/445_20250210142542083.png"],
        ["id" => "564", "name" => "Mahjong Titan", "img" => "https://ossimg.91admin123admin.com/91club/gamelogo/Card365/564_20250210142932446.png"],
        ["id" => "567", "name" => "Bursting QX", "img" => "https://ossimg.91admin123admin.com/91club/gamelogo/Card365/567_20250210143012425.png"],
        ["id" => "569", "name" => "Rapid 3 Pictures", "img" => "https://ossimg.91admin123admin.com/91club/gamelogo/Card365/569_20250210143027067.png"],
        ["id" => "703", "name" => "Bursting 3 Pictures", "img" => "https://ossimg.91admin123admin.com/91club/gamelogo/Card365/703_20250210143141953.png"],
        ["id" => "912", "name" => "Vietnam Fish Prawn Crab", "img" => "https://ossimg.91admin123admin.com/91club/gamelogo/Card365/912_20250210142423424.png"],
        ["id" => "548", "name" => "Bursting Baccarat", "img" => "https://ossimg.91admin123admin.com/91club/gamelogo/Card365/548_20250210142617774.png"],
        ["id" => "916", "name" => "Vietnam TÃ i-Xá»‰u", "img" => "https://ossimg.91admin123admin.com/91club/gamelogo/Card365/916_20250210142453957.png"],
    ];
@endphp

@foreach ($card365Games as $game)
    <div class="swiper-slide game-item-box game-card" data-category="card365">
        @auth
            <a href="{{ url('user/jili/launch?game_code='.$game['id'].'&provider=card365') }}" class="game-card-img">
        @else
            <a href="{{ route('user.login') }}" class="game-card-img">
        @endauth
                <img src="{{ $game['img'] }}" alt="{{ $game['name'] }}">
            </a>
    </div>
@endforeach