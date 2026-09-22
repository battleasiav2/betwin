<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Log;

class LogCategoryErrors
{
    public function handle($request, Closure $next)
    {
        if ($request->has('_category_error_log') || $request->header('X-Category-Error-Log')) {
            $data = $request->input('log_data');
            Log::channel('daily')->warning('CATEGORY FILTER ERROR', [
                'error' => $data,
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
                'user' => auth()->id() ?? 'guest',
                'time' => now()->toDateTimeString()
            ]);
            return response()->json(['logged' => true]);
        }
        return $next($request);
    }
}