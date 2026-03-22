<?php

namespace App\Http\Middleware;

use App\Models\Currency;
use App\Services\CurrencyService;
use Closure;
use Illuminate\Http\Request;

class SetCurrency
{
    public function __construct(protected CurrencyService $currencyService) {}

    public function handle(Request $request, Closure $next)
    {
        // Accept either header:
        //   X-Currency: USD
        //   X-Currency-Id: 3
        $code = $request->header('X-Currency');
        $id   = $request->header('X-Currency-Id');

        $currency = null;

        if ($code) {
            $currency = Currency::where('code', strtoupper($code))
                                ->where('is_active', true)
                                ->first();
        } elseif ($id) {
            $currency = Currency::where('id', $id)
                                ->where('is_active', true)
                                ->first();
        }

        // Fall back to default if header is missing or currency not found
        if ($currency) {
            $this->currencyService->setCurrency($currency);
        }

        return $next($request);
    }
}