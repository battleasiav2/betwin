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
        $sportsLobbies = [
            'luckysport' => '92b24e4c25107367a80e0fe1a97c24e4',
            'sabasports' => '08ced9dd788aed11ff3c7f387ae0f063',
            'bti' => '4d31f1186a81e208c003a7e37411ce35',
            'cmd' => '1f7fbf84bf1bcc08c3a7ea27db75f366',
            'ug' => 'c4b2813f6bbc5abf502ddfb857e604eb',
            'sbo' => '341827d4370bb198b18364e2d75e6916',
            'tf' => '4ee8e0051a035b463b47c3c473ce317d',
            'dpsports' => '23c2dca76f87d7b7f239833060c8751e',
            'dpesports' => 'e130116fdc9bcde2dbb31735b6c365d6',
        ];
        $launch = isset($sportsLobbies[$slug])
            ? url('user/jili/launch?game_code='.$sportsLobbies[$slug].'&provider='.$slug)
            : '';
        $initials = strtoupper(mb_substr(preg_replace('/[^A-Za-z0-9]/', '', $p['name']), 0, 2) ?: 'P');
        $colors = ['#123b66','#0f766e','#9a3412','#1d4ed8','#7c2d12','#4c1d95','#065f46','#9f1239'];
        $bg = $colors[crc32($slug) % count($colors)];
    @endphp
    <div class="provider-card"
         data-key="{{ $slug }}"
         data-type="{{ $p['type'] }}"
         data-has-games="{{ !empty($p['has_games']) ? '1' : '0' }}"
         data-vendor="{{ $p['vendor'] }}"
         @if($launch) data-launch="{{ $launch }}" @endif
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
