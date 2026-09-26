@php
    $img = $game['img'] ?? '';
    $prov = $game['provider'] ?? 'jili';
    if (empty($game['provider'])) {
        if (stripos($img, 'spribe') !== false) $prov = 'spribe';
        elseif (preg_match('#gamelogo/PG/#i', $img)) $prov = 'pg';
        elseif (preg_match('#gamelogo/JDB/#i', $img)) $prov = 'jdb';
        elseif (preg_match('#EVO_Video|/EVO/#i', $img)) $prov = 'evo';
        elseif (preg_match('#/api/km/|/km/#i', $img)) $prov = 'km';
        elseif (preg_match('#gamelogo/MG/#i', $img)) $prov = 'mg';
        elseif (preg_match('#gamelogo/CQ9/#i', $img)) $prov = 'cq9';
        elseif (preg_match('#gamelogo/JILI/#i', $img)) $prov = 'jili';
    }
    $labels = [
        'spribe' => 'SPRIBE',
        'pg' => 'PG SOFT',
        'jdb' => 'JDB',
        'evo' => 'EVOLUTION',
        'km' => 'KM',
        'mg' => 'MG',
        'jili' => 'JILI GAMES',
        'cq9' => 'CQ9',
    ];
    $label = $labels[$prov] ?? strtoupper($prov);
    $tints = ['#0f7a3a', '#b91c1c', '#1d4ed8', '#7c3aed', '#0f766e', '#b45309', '#be185d', '#0369a1'];
    $tint = $tints[abs(crc32((string) ($game['id'] ?? $game['name']))) % count($tints)];
    $playing = number_format(max(18, (abs(crc32((string) ($game['id'] ?? 'x'))) % 2400) + 12));
    $href = auth()->check()
        ? url('user/jili/launch?game_code='.$game['id'].'&provider='.$prov)
        : route('user.login');
@endphp
<div class="game-item-box game-card showcase-card" data-category="{{ $category ?? 'hot' }}" data-provider="{{ $prov }}" data-game-id="{{ $game['id'] }}" data-game-name="{{ $game['name'] }}" data-game-img="{{ $img }}">
    <a href="{{ $href }}" class="showcase-card__link" title="{{ $game['name'] }}" style="--sc-tint: {{ $tint }}">
        <span class="showcase-card__badge">{{ $label }}</span>
        <span class="showcase-card__title">{{ $game['name'] }}</span>
        <span class="showcase-card__art">
            <img src="{{ $img }}" alt="{{ $game['name'] }}" loading="lazy" decoding="async" referrerpolicy="no-referrer">
        </span>
    </a>
    <div class="showcase-card__meta">
        <span class="showcase-card__dot" aria-hidden="true"></span>
        <span>{{ $playing }} playing</span>
    </div>
</div>
