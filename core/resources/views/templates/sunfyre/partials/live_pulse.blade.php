@php
    $pulseWindow = function ($end) {
        $end = \Illuminate\Support\Carbon::parse($end)->startOfDay();

        return collect(range(6, 0))->map(fn ($i) => $end->copy()->subDays($i)->toDateString());
    };
    $pulseSeries = function (string $table, string $column, bool $sum = false, $scope = null) use ($pulseWindow) {
        if (!\Illuminate\Support\Facades\Schema::hasTable($table)) {
            return array_fill(0, 7, 0);
        }
        $collect = function ($from, $days) use ($table, $column, $sum, $scope) {
            $query = \Illuminate\Support\Facades\DB::table($table)->where($column, '>=', $from);
            if ($scope) {
                $scope($query);
            }
            $raw = $sum
                ? $query->selectRaw('DATE('.$column.') as d, COALESCE(SUM(win_amo),0) as c')
                : $query->selectRaw('DATE('.$column.') as d, COUNT(*) as c');
            $rows = $raw->groupBy('d')->pluck('c', 'd');

            return $days->map(fn ($day) => (int) round((float) ($rows[$day] ?? 0)))->all();
        };
        $days = $pulseWindow(now());
        $series = $collect($days->first(), $days);
        $active = count(array_filter($series, fn ($value) => $value > 0));
        if ($active >= 2) {
            return $series;
        }
        $latestQuery = \Illuminate\Support\Facades\DB::table($table)
            ->where($column, '>=', now()->subDays(420)->startOfDay());
        if ($scope) {
            $scope($latestQuery);
        }
        if ($sum) {
            $latestQuery->where('win_amo', '>', 0);
        }
        $latest = $latestQuery->max($column);
        if (!$latest) {
            return $series;
        }
        $days = $pulseWindow($latest);

        return $collect($days->first(), $days);
    };

    $joinSeries = $pulseSeries('users', 'created_at');
    $matchSeries = \Illuminate\Support\Facades\Schema::hasTable('live_matches')
        ? $pulseSeries('live_matches', 'created_at')
        : array_fill(0, 7, 0);
    $winSeries = $pulseSeries('game_logs', 'created_at', true);

    $todayJoins = (int) \Illuminate\Support\Facades\DB::table('users')->whereDate('created_at', now()->toDateString())->count();
    $matchTotal = \Illuminate\Support\Facades\Schema::hasTable('live_matches')
        ? (int) \App\Models\LiveMatch::where('status', 1)->count()
        : 0;
    $ongoing = \Illuminate\Support\Facades\Schema::hasTable('live_matches')
        ? (int) \App\Models\LiveMatch::where('status', 1)->where('is_live', 1)->count()
        : 0;
    $winnings = (int) round((float) \Illuminate\Support\Facades\DB::table('game_logs')->where('win_status', \App\Constants\Status::WIN)->sum('win_amo'));

    $pulseStats = [
        ['label' => 'TODAY JOINS', 'value' => number_format($todayJoins), 'series' => $joinSeries, 'dot' => false, 'coin' => false],
        ['label' => 'MATCHES', 'value' => number_format($matchTotal), 'series' => $matchSeries, 'dot' => false, 'coin' => false],
        ['label' => 'ONGOING', 'value' => number_format($ongoing), 'series' => [], 'dot' => true, 'coin' => false],
        ['label' => 'WINNINGS', 'value' => number_format($winnings), 'series' => $winSeries, 'dot' => false, 'coin' => true],
    ];
@endphp
<section class="lp-bar" aria-label="Live pulse">
    <div class="lp-badge"><i></i><span>LIVE PULSE</span></div>
    <div class="lp-stats">
        @foreach($pulseStats as $stat)
        <div class="lp-stat">
            <div class="lp-label">{{ $stat['label'] }}</div>
            <div class="lp-value">
                @if($stat['dot'])<span class="lp-dot"></span>@endif
                <span>{{ $stat['value'] }}</span>
                @if($stat['coin'])<span class="lp-coin">{{ gs('cur_sym') }}</span>@endif
            </div>
            <div class="lp-line">
                @php $barMax = count($stat['series']) ? max($stat['series']) : 0; @endphp
                @if($barMax > 0)
                <svg viewBox="0 0 76 24" aria-hidden="true">
                    @foreach($stat['series'] as $barIndex => $barValue)
                        @if($barValue > 0)
                        @php
                            $barH = max(8, ($barValue / $barMax) * 20);
                            $barX = 3 + ($barIndex * 10.5);
                            $barY = 22 - $barH;
                        @endphp
                        <rect x="{{ round($barX, 1) }}" y="{{ round($barY, 1) }}" width="7" height="{{ round($barH, 1) }}" rx="1.5" fill="#8fd4ff" />
                        @endif
                    @endforeach
                </svg>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</section>
<style>
    .lp-bar {
        display: flex;
        flex-direction: column;
        align-items: stretch;
        gap: 10px;
        position: relative;
        z-index: 3;
        margin: 16px 10px 10px;
        padding: 12px 10px 10px;
        border: 1px solid rgba(255,255,255,0.16);
        border-radius: 16px;
        background: #141824;
        overflow: visible;
        clear: both;
    }
    .lp-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        align-self: flex-start;
        gap: 8px;
        height: 28px;
        padding: 0 10px;
        border-radius: 999px;
        background: #1c2433;
        color: #d5dde8;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.4px;
        white-space: nowrap;
    }
    .lp-badge i {
        width: 16px;
        height: 10px;
        border-radius: 999px;
        background: #2f6bff;
        box-shadow: inset 6px 0 0 #8eb4ff;
        flex-shrink: 0;
    }
    .lp-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 6px;
        width: 100%;
    }
    .lp-stat {
        min-width: 0;
        text-align: center;
        font-family: "Segoe UI", system-ui, sans-serif;
    }
    .lp-label {
        height: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #d5dde8;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.5px;
        line-height: 1;
    }
    .lp-value {
        height: 30px;
        display: flex;
        align-items: baseline;
        justify-content: center;
        gap: 4px;
        color: #fff;
        font-size: 22px;
        font-weight: 800;
        line-height: 1;
        font-variant-numeric: tabular-nums lining-nums;
        font-feature-settings: "tnum" 1, "lnum" 1;
    }
    .lp-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #3ddc84;
        align-self: center;
        box-shadow: 0 0 0 3px rgba(61,220,132,0.15);
    }
    .lp-coin {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        align-self: center;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: radial-gradient(circle at 35% 35%, #ffe08a, #e8b84a 55%, #a97820);
        color: #5a3d08;
        font-size: 9px;
        font-weight: 900;
        line-height: 1;
    }
    .lp-line {
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: visible;
    }
    .lp-line svg { width: 76px; height: 24px; display: block; overflow: visible; }
    @media (max-width: 720px) {
        .lp-label { font-size: 9px; letter-spacing: 0.2px; }
        .lp-value { font-size: 16px; }
        .lp-line svg { width: 64px; }
    }
</style>
