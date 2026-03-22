<?php

namespace App\Services;

use App\Models\Currency;
use Illuminate\Support\Facades\Cache;

class CurrencyService
{
    protected Currency $currency;

    public function __construct()
    {
        // Resolved in SetCurrency middleware; falls back to default
        $this->currency = Currency::getDefault();
    }

    public function setCurrency(Currency $currency): void
    {
        $this->currency = $currency;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    /**
     * Convert a price from the base currency to the active currency.
     * All prices stored in DB are assumed to be in the base/default currency.
     */
    public function convert(?float $amount): ?float
    {
        if (is_null($amount)) {
            return null;
        }

        return round($amount * $this->currency->exchange_rate, 2);
    }

    /**
     * Return a formatted string with symbol, e.g. "$ 29.99"
     */
    public function format(?float $amount): ?string
    {
        if (is_null($amount)) {
            return null;
        }

        return $this->currency->symbol . ' ' . number_format($this->convert($amount), 2);
    }

    /**
     * Attach currency meta to any price array.
     * Handy for building response summaries.
     */
    public function meta(): array
    {
        return [
            'code'   => $this->currency->code,
            'symbol' => $this->currency->symbol,
            'rate'   => $this->currency->exchange_rate,
        ];
    }

    /**
     * Convert back from the active currency to base (for storing orders etc.)
     */
    public function toBase(float $amount): float
    {
        if ($this->currency->exchange_rate == 0) {
            return $amount;
        }

        return round($amount / $this->currency->exchange_rate, 2);
    }
}