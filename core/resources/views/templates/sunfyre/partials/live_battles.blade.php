@php
    $battleVariant = $battleVariant ?? 'live';
    $isPrize = $battleVariant === 'prize';
    $liveMatches = collect();
    if (\Illuminate\Support\Facades\Schema::hasTable('live_matches')) {
        $matchQuery = \App\Models\LiveMatch::query()->where('status', 1);
        if ($isPrize) {
            $matchQuery->orderByDesc('prize')->orderBy('id');
        } else {
            $matchQuery->where('is_live', 1)->orderBy('sort_order')->orderBy('id');
        }
        $liveMatches = $matchQuery->when(empty($showAllMatches), fn ($q) => $q->limit(8))->get();
    }
    $joinUrl = auth()->check() ? route('user.deposit.index') : route('user.login');
    $rowId = $isPrize ? 'hpRow' : 'lbRow';
@endphp
@if($liveMatches->isNotEmpty())
<style>
    .lb-wrap { margin: 14px 12px 6px; }
    .lb-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; }
    .lb-head h2 { margin: 0; font-size: 16px; font-weight: 900; letter-spacing: 0.3px; color: #fff; display: flex; align-items: center; gap: 8px; }
    .lb-arrows { display: flex; gap: 8px; }
    .lb-arrows button {
        width: 32px; height: 32px; border-radius: 10px; border: 1px solid rgba(255,255,255,0.12);
        background: #151b24; color: #fff; cursor: pointer;
    }
    .lb-row { display: flex; gap: 12px; overflow-x: auto; scroll-snap-type: x mandatory; padding-bottom: 6px; }
    .lb-row::-webkit-scrollbar { display: none; }
    .lb-card {
        flex: 0 0 240px; scroll-snap-align: start; background: #16161f; border: 1px solid rgba(255,255,255,0.08);
        border-radius: 16px; padding: 12px 12px 14px; color: #fff;
    }
    .lb-top { display: flex; align-items: center; justify-content: space-between; font-size: 11px; color: #9aa3b2; font-weight: 700; }
    .lb-live { color: #3ddc84; font-size: 10px; font-weight: 800; letter-spacing: 0.4px; }
    .lb-live i { font-size: 8px; margin-right: 4px; }
    .lb-title { margin: 8px 0 10px; font-size: 15px; font-weight: 800; line-height: 1.25; min-height: 38px; }
    .lb-wrap.is-live {
        padding: 10px 10px 8px;
        border-radius: 16px;
        background: rgba(45, 212, 168, 0.08);
        border: 1px solid rgba(45, 212, 168, 0.22);
    }
    .lb-wrap.is-prize {
        padding: 10px 10px 8px;
        border-radius: 16px;
        background: rgba(232, 184, 74, 0.1);
        border: 1px solid rgba(232, 184, 74, 0.32);
    }
    .lb-wrap.is-prize h2 { color: #f0d078; }
    .lb-wrap.is-prize .lb-card {
        background: #1a160e;
        border-color: rgba(232, 184, 74, 0.28);
    }
    .lb-pulse {
        width: 9px; height: 9px; border-radius: 50%; background: #3ddc84; display: inline-block;
        box-shadow: 0 0 0 0 rgba(61, 220, 132, 0.7);
        animation: lbPulse 1.6s ease-out infinite;
    }
    @keyframes lbPulse {
        70% { box-shadow: 0 0 0 8px rgba(61, 220, 132, 0); }
        100% { box-shadow: 0 0 0 0 rgba(61, 220, 132, 0); }
    }
    .lb-spots { font-size: 14px; color: #ffffff; font-weight: 800; margin-bottom: 10px; letter-spacing: 0.3px; }
    .lb-meta { display: flex; justify-content: space-between; gap: 8px; margin-bottom: 12px; }
    .lb-meta span { display: block; font-size: 13px; color: #ffffff; font-weight: 800; letter-spacing: 0.5px; }
    .lb-meta strong { display: block; margin-top: 4px; font-size: 20px; color: #fff; font-variant-numeric: tabular-nums; }
    .lb-join {
        display: block; text-align: center; text-decoration: none; color: #fff; font-weight: 800; font-size: 13px;
        padding: 10px 12px; border-radius: 999px;
        background: linear-gradient(90deg, #7b5cff 0%, #3ec6ff 100%);
    }
    .lb-all { display: inline-block; margin-top: 8px; color: #b9a6ff; font-size: 12px; font-weight: 800; text-decoration: none; letter-spacing: 0.4px; }
</style>
<section class="lb-wrap {{ $isPrize ? 'is-prize' : 'is-live' }}" id="{{ $isPrize ? 'high-prize-battles' : 'live-battles' }}">
    <div class="lb-head">
        <h2>
            @if(!$isPrize)<span class="lb-pulse" aria-hidden="true"></span>@endif
            {{ $isPrize ? 'HIGH-PRIZE BATTLES' : 'LIVE BATTLES' }}
        </h2>
        @if(empty($showAllMatches))
        <div class="lb-arrows">
            <button type="button" class="lb-prev" aria-label="Previous"><i class="fas fa-chevron-left"></i></button>
            <button type="button" class="lb-next" aria-label="Next"><i class="fas fa-chevron-right"></i></button>
        </div>
        @endif
    </div>
    <div class="lb-row" id="{{ $rowId }}">
        @foreach($liveMatches as $match)
        <article class="lb-card">
            <div class="lb-top">
                <span>{{ $match->game_name }}</span>
                @if($match->is_live)
                    <span class="lb-live"><i class="fas fa-circle"></i>LIVE</span>
                @endif
            </div>
            <h3 class="lb-title">{{ $match->title }}</h3>
            <div class="lb-spots">SPOTS · {{ $match->spots_filled }} / {{ $match->spots_total }}</div>
            <div class="lb-meta">
                <div>
                    <span>ENTRY</span>
                    <strong>{{ number_format($match->entry_fee, 0) }}</strong>
                </div>
                <div>
                    <span>PRIZE EST.</span>
                    <strong>{{ number_format($match->prize, 0) }}</strong>
                </div>
            </div>
            <a class="lb-join" href="{{ $joinUrl }}">JOIN NOW →</a>
        </article>
        @endforeach
    </div>
    @if(empty($showAllMatches))
        <a class="lb-all" href="{{ route('user.matches') }}">VIEW ALL →</a>
    @endif
</section>
@if(empty($showAllMatches))
<script>
    (function () {
        var row = document.getElementById(@json($rowId));
        if (!row) return;
        var step = 252;
        var prev = row.parentElement.querySelector('.lb-prev');
        var next = row.parentElement.querySelector('.lb-next');
        if (prev) prev.addEventListener('click', function () { row.scrollBy({ left: -step, behavior: 'smooth' }); });
        if (next) next.addEventListener('click', function () { row.scrollBy({ left: step, behavior: 'smooth' }); });
    })();
</script>
@endif
@endif
