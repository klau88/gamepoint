<?php

namespace App\Services;

use Illuminate\Support\Collection;

class MapOrderService
{
    /**
     * @param array $data
     * @return Collection
     */
    public function map(array $data): Collection
    {
        return collect($data)->map(function ($amount, $key) {
            return [
                'key' => $key,
                'value' => sprintf('%s %s', $amount['currency'], number_format($amount['amount'], 2))
            ];
        });
    }
}
