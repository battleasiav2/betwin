@extends($activeTemplate . 'layouts.master')
@section('content')
<style>
    .agent-page { max-width: 720px; margin: 0 auto; padding: 16px 14px 96px; color: #e8eef5; }
    .agent-card { background: rgba(15, 20, 25, 0.92); border: 1px solid rgba(232, 184, 74, 0.22); border-radius: 16px; padding: 16px; margin-bottom: 14px; }
    .agent-card h1, .agent-card h2 { margin: 0 0 8px; font-size: 18px; color: #e8b84a; }
    .agent-muted { color: #8b97a8; font-size: 13px; line-height: 1.5; }
    .agent-stat { display: flex; justify-content: space-between; gap: 12px; padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.06); }
    .agent-stat b { color: #2dd4a8; }
    .agent-link { width: 100%; margin-top: 10px; background: #0b0f14; color: #e8eef5; border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 10px; font-size: 13px; }
    .agent-btn { width: 100%; margin-top: 10px; border: 0; border-radius: 12px; padding: 12px; background: linear-gradient(135deg, #e8b84a, #c9962e); color: #1a1203; font-weight: 700; }
    .agent-input { width: 100%; margin-top: 8px; background: #0b0f14; color: #e8eef5; border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; padding: 12px; }
    .agent-table { width: 100%; border-collapse: collapse; font-size: 13px; }
    .agent-table th, .agent-table td { text-align: left; padding: 8px 4px; border-bottom: 1px solid rgba(255,255,255,0.06); }
    .agent-table th { color: #8b97a8; font-weight: 600; }
    .agent-empty { color: #8b97a8; padding: 12px 0; }
    .agent-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
    .agent-box { background: #0b0f14; border-radius: 12px; padding: 10px 12px; }
    .agent-box span { display: block; color: #8b97a8; font-size: 12px; margin-bottom: 4px; }
    .agent-box b { color: #2dd4a8; font-size: 16px; }
</style>
<div class="agent-page">
    @if(!$user->is_agent)
        <div class="agent-card">
            <h1>Agent</h1>
            <p class="agent-muted">আপনি এখনো এজেন্ট নন। অ্যাডমিন নিয়োগ দিলে এই পেজে কমিশন ও লিংক দেখা যাবে।</p>
        </div>
    @else
        @php $link = url('/?reference=' . $user->username); @endphp
        <div class="agent-card">
            <h1>Agent</h1>
            <div class="agent-stat"><span>কমিশন রেট</span><b>{{ getAmount($user->agent_percent) }}%</b></div>
            <div class="agent-stat"><span>এজেন্ট ব্যালেন্স</span><b>{{ showAmount($user->agent_balance) }}</b></div>
            <div class="agent-grid">
                <div class="agent-box"><span>আজকে কমিশন</span><b>{{ showAmount($stats['commission_today']) }}</b></div>
                <div class="agent-box"><span>গতকাল</span><b>{{ showAmount($stats['commission_yesterday']) }}</b></div>
                <div class="agent-box"><span>এই মাস</span><b>{{ showAmount($stats['commission_month']) }}</b></div>
                <div class="agent-box"><span>গত মাস</span><b>{{ showAmount($stats['commission_last_month']) }}</b></div>
                <div class="agent-box"><span>মোট কমিশন</span><b>{{ showAmount($stats['commission_total']) }}</b></div>
                <div class="agent-box"><span>ওয়ালেটে নেওয়া</span><b>{{ showAmount($stats['moved_total']) }}</b></div>
            </div>
            <p class="agent-muted">এই লিংকে রেজিস্ট্রেশন করলে সে আপনার প্লেয়ার হবে। সফল ডিপোজিটের উপর কমিশন এই ব্যালেন্সে যোগ হবে। প্লে ব্যালেন্সে না, তাই গেমে নিজে থেকে কাটবে না।</p>
            <input class="agent-link" id="agentLink" value="{{ $link }}" readonly>
            <button type="button" class="agent-btn" onclick="navigator.clipboard.writeText(document.getElementById('agentLink').value)">লিংক কপি করুন</button>
        </div>

        <div class="agent-card">
            <h2>ওয়ালেটে নিন</h2>
            <p class="agent-muted">কমিশন প্লে ওয়ালেটে নিলে উইথড্র করতে অ্যাডমিনের অনুমোদন লাগবে।</p>
            <form method="POST" action="{{ route('user.agent.transfer') }}">
                @csrf
                <input class="agent-input" type="number" name="amount" min="1" step="0.01" max="{{ getAmount($user->agent_balance) }}" placeholder="কমপক্ষে ১" required>
                <button class="agent-btn" type="submit">প্লে ওয়ালেটে নিন</button>
            </form>
        </div>

        <div class="agent-card">
            <h2>প্লেয়ার ও ডিপোজিট</h2>
            <div class="agent-grid">
                <div class="agent-box"><span>মোট প্লেয়ার</span><b>{{ $stats['players_total'] }}</b></div>
                <div class="agent-box"><span>আজ নতুন</span><b>{{ $stats['players_today'] }}</b></div>
                <div class="agent-box"><span>এই মাসে যোগ</span><b>{{ $stats['players_month'] }}</b></div>
                <div class="agent-box"><span>গত মাসে যোগ</span><b>{{ $stats['players_last_month'] }}</b></div>
                <div class="agent-box"><span>আজকের ডিপোজিট</span><b>{{ showAmount($stats['deposit_today']) }}</b></div>
                <div class="agent-box"><span>গতকালের ডিপোজিট</span><b>{{ showAmount($stats['deposit_yesterday']) }}</b></div>
                <div class="agent-box"><span>এই মাসের ডিপোজিট</span><b>{{ showAmount($stats['deposit_month']) }}</b></div>
                <div class="agent-box"><span>গত মাসের ডিপোজিট</span><b>{{ showAmount($stats['deposit_last_month']) }}</b></div>
                <div class="agent-box"><span>মোট ডিপোজিট</span><b>{{ showAmount($stats['deposit_total']) }}</b></div>
                <div class="agent-box"><span>এই মাসে ওয়ালেটে</span><b>{{ showAmount($stats['moved_month']) }}</b></div>
            </div>
        </div>

        <div class="agent-card">
            <h2>আমার প্লেয়ার</h2>
            @if($downline->count())
                <table class="agent-table">
                    <thead>
                        <tr><th>ইউজার</th><th>যোগ</th><th>সফল ডিপোজিট</th></tr>
                    </thead>
                    <tbody>
                        @foreach($downline as $player)
                            <tr>
                                <td>{{ $player->username }}</td>
                                <td>{{ showDateTime($player->created_at, 'd M Y') }}</td>
                                <td>{{ showAmount($depositTotals[$player->id] ?? 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($downline->hasPages())
                    <div style="margin-top:12px;">{{ $downline->links() }}</div>
                @endif
            @else
                <p class="agent-empty">এখনো কেউ আপনার লিংকে যোগ দেয়নি।</p>
            @endif
        </div>

        <div class="agent-card">
            <h2>কমিশন হিস্ট্রি</h2>
            @if($logs->count())
                <table class="agent-table">
                    <thead>
                        <tr><th>প্লেয়ার</th><th>ডিপোজিট</th><th>কমিশন</th><th>সময়</th></tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                            <tr>
                                <td>{{ $log->userFrom->username ?? '-' }}</td>
                                <td>{{ showAmount($depositAmounts[$log->trx] ?? 0) }}</td>
                                <td>{{ showAmount($log->amount) }}</td>
                                <td>{{ showDateTime($log->created_at, 'd M, h:i A') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($logs->hasPages())
                    <div style="margin-top:12px;">{{ $logs->links() }}</div>
                @endif
            @else
                <p class="agent-empty">এখনো কমিশন পড়েনি।</p>
            @endif
        </div>

        <div class="agent-card">
            <h2>ওয়ালেটে নেওয়ার হিস্ট্রি</h2>
            @if($transfers->count())
                <table class="agent-table">
                    <thead>
                        <tr><th>পরিমাণ</th><th>প্লে ব্যালেন্স</th><th>সময়</th></tr>
                    </thead>
                    <tbody>
                        @foreach($transfers as $move)
                            <tr>
                                <td>{{ showAmount($move->amount) }}</td>
                                <td>{{ showAmount($move->post_balance) }}</td>
                                <td>{{ showDateTime($move->created_at, 'd M, h:i A') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($transfers->hasPages())
                    <div style="margin-top:12px;">{{ $transfers->links() }}</div>
                @endif
            @else
                <p class="agent-empty">এখনো কমিশন ওয়ালেটে নেননি।</p>
            @endif
        </div>
    @endif
</div>
@endsection
