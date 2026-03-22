<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Traits\Responses;

class CurrencyController extends Controller
{
    use Responses;

    /**
     * GET /api/v1/currencies
     * Returns all active currencies so the app can build a picker.
     */
    public function index()
    {
        $currencies = Currency::where('is_active', true)
            ->orderByDesc('is_default')
            ->get(['id', 'name', 'code', 'symbol', 'exchange_rate', 'is_default']);

        return $this->success_response('Currencies retrieved successfully', $currencies);
    }
}

