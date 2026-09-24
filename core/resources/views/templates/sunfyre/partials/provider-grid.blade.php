@php
    $allProviders = config('rapidverse_providers', []);
    // Prefer DB status when present; default ON for sheet providers
    $statusMap = $gameStatus ?? collect();
@endphp

<div class="provider-grid" id="rv-provider-grid">
@foreach ($allProviders as $p)
    @php
        $slug = $p['slug'];
        $st = isset($statusMap[$slug]) ? (int) $statusMap[$slug]->status : 1;
        if ($st === 0) continue;
        $logo = $p['logo'] ?? '';
        $initials = strtoupper(mb_substr(preg_replace('/[^A-Za-z0-9]/', '', $p['name']), 0, 2) ?: 'P');
        $colors = ['#123b66','#0f766e','#9a3412','#1d4ed8','#7c2d12','#4c1d95','#065f46','#9f1239'];
        $bg = $colors[crc32($slug) % count($colors)];
    @endphp
    <div class="provider-card"
         data-key="{{ $slug }}"
         data-type="{{ $p['type'] }}"
         data-has-games="{{ !empty($p['has_games']) ? '1' : '0' }}"
         data-vendor="{{ $p['vendor'] }}"
         onclick="selectProvider('{{ $slug }}')">
        @if ($logo)
            <img src="{{ $logo }}" alt="{{ $p['name'] }}" loading="lazy" referrerpolicy="no-referrer"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <div class="provider-fallback" style="display:none;width:100%;height:48px;align-items:center;justify-content:center;background:{{ $bg }};color:#fff;font-weight:800;font-size:16px;border-radius:8px;">{{ $initials }}</div>
        @else
            <div class="provider-fallback" style="display:flex;width:100%;height:48px;align-items:center;justify-content:center;background:{{ $bg }};color:#fff;font-weight:800;font-size:16px;border-radius:8px;">{{ $initials }}</div>
        @endif
        <span>{{ $p['name'] }}</span>
    </div>
@endforeach
</div>
