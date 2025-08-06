<?php

namespace App\Services;

use App\Models\Payment;
use Illuminate\Pagination\LengthAwarePaginator;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class PaginateService
{
    /**
     * @param $orderBy
     * @param $pageName
     * @param $order
     * @param $perPage
     * @param $chunkSize
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function payments($orderBy, $pageName, $order = 'asc', $perPage = 25, $chunkSize = 100): array
    {
        $total = [];
        Payment::orderBy($orderBy, $order)->chunk($chunkSize, function ($payments) use (&$total, $orderBy) {
            foreach ($payments as $payment) {
                $key = $payment[$orderBy];
                $amount = ($payment['amount_in_cents'] / 100);
                if (!isset($total[$key])) {
                    $total[$key]['amount'] = 0;
                    $total[$key]['currency'] = $payment['currency'];
                    $total[$key]['date'] = $payment['date'];
                }

                $total[$key]['amount'] += $amount;
            }
        });

        $page = request()->get($pageName, 1);
        $collection = collect($total);
        $paginatedItems = $collection->slice(($page - 1) * $perPage, $perPage)->all();

        return (new LengthAwarePaginator(
            $paginatedItems,
            $collection->count(),
            $perPage,
            $page,
            [
                'path' => request()->url(),
                'query' => request()->query(),
                'pageName' => $pageName
            ]
        ))->toArray();
    }
}
