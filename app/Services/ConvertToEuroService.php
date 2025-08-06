<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Worksome\Exchange\Facades\Exchange;

class ConvertToEuroService
{
    /**
     * @param array $data
     * @return Collection
     */
    public function convert(array $data): Collection
    {
        return collect($data)->map(function ($amount, $date) {
            if ($amount['currency'] !== 'EUR') {
                $exchangeRates = Exchange::rates($amount['currency'], ['EUR'])->getRates();
                $rate = $exchangeRates['EUR'];

                $amount['amount'] *= $rate;
            }

            return [
                'key' => $date,
                'value' => sprintf('€ %s', number_format($amount['amount'], 2))
            ];
        });
    }
}
