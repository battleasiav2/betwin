<?php

namespace App\Lib;

use App\Constants\Status;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\Cache;

class LiveWithdrawFeed
{
    public static function items(int $limit = 24): array
    {
        return Cache::remember('live_withdraw_feed_v1', 20, function () use ($limit) {
            $currency = (string) (gs('cur_text') ?: 'BDT');
            $items = [];

            try {
                $rows = Withdrawal::query()
                    ->approved()
                    ->with(['user:id,mobile,username,firstname,image'])
                    ->orderByDesc('updated_at')
                    ->limit($limit)
                    ->get();

                foreach ($rows as $row) {
                    $mobile = (string) ($row->user->mobile ?? $row->user->username ?? '');
                    if ($mobile === '') {
                        continue;
                    }
                    $items[] = self::formatItem(
                        $mobile,
                        (float) $row->amount,
                        $currency,
                        (string) ($row->user->image ?? ''),
                        optional($row->updated_at)->diffForHumans() ?: 'just now',
                        true
                    );
                }
            } catch (\Throwable $e) {
                // keep going with synthetic feed
            }

            if (count($items) < $limit) {
                $need = $limit - count($items);
                $users = User::query()
                    ->whereNotNull('mobile')
                    ->where('mobile', '!=', '')
                    ->where('status', Status::USER_ACTIVE)
                    ->inRandomOrder()
                    ->limit(max($need * 2, 20))
                    ->get(['id', 'mobile', 'username', 'image']);

                $amounts = [500, 800, 1000, 1200, 1500, 2000, 2500, 3000, 4500, 5000, 6500, 8000, 10000, 12000, 15000, 20000, 25000, 32000, 45000, 50000, 68000, 85000];
                $used = [];

                foreach ($users as $user) {
                    if (count($items) >= $limit) {
                        break;
                    }
                    $mobile = preg_replace('/\D+/', '', (string) $user->mobile);
                    if (strlen($mobile) < 10 || isset($used[$mobile])) {
                        continue;
                    }
                    $used[$mobile] = true;
                    $seed = crc32($mobile . date('YmdH'));
                    $amount = $amounts[$seed % count($amounts)];
                    $mins = 1 + ($seed % 45);
                    $items[] = self::formatItem(
                        $mobile,
                        (float) $amount,
                        $currency,
                        (string) ($user->image ?? ''),
                        $mins <= 1 ? 'just now' : $mins . ' min ago',
                        false
                    );
                }
            }

            // Keep feed feeling "live": shuffle lightly but keep newest-looking first.
            usort($items, function ($a, $b) {
                return ($b['sort'] ?? 0) <=> ($a['sort'] ?? 0);
            });

            return array_values(array_slice($items, 0, $limit));
        });
    }

    protected static function formatItem(string $mobile, float $amount, string $currency, string $image, string $when, bool $real): array
    {
        $digits = preg_replace('/\D+/', '', $mobile);
        $masked = self::maskMobile($digits);
        $avatar = self::avatarUrl($digits, $image);

        return [
            'mobile'   => $masked,
            'amount'   => $amount,
            'amount_f' => number_format($amount, 0) . ' ' . $currency,
            'avatar'   => $avatar,
            'when'     => $when,
            'winner'   => true,
            'real'     => $real,
            'sort'     => $real ? 1000 + (int) $amount : (int) $amount,
        ];
    }

    public static function maskMobile(string $digits): string
    {
        $digits = preg_replace('/\D+/', '', $digits);
        if (strlen($digits) < 8) {
            return $digits;
        }
        return substr($digits, 0, 3) . '****' . substr($digits, -4);
    }

    public static function avatarUrl(string $seed, string $image = ''): string
    {
        if ($image !== '') {
            try {
                return getImage(getFilePath('userProfile') . '/' . $image, getFileSize('userProfile'));
            } catch (\Throwable $e) {
                // fall through
            }
        }

        $safe = preg_replace('/[^a-zA-Z0-9]/', '', $seed) ?: 'user';
        return 'https://api.dicebear.com/7.x/adventurer/svg?seed=' . urlencode($safe) . '&backgroundColor=0b1220,123b66,0f766e';
    }
}
