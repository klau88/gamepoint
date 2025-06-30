<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Inertia\Inertia;
use Worksome\Exchange\Facades\Exchange;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;

class PaymentController extends Controller
{
    public function dashboard()
    {
        $orderedByUser = $this->paginated(orderBy: 'user_id', pageName: 'usersPage');

        $sumPerUser = collect($orderedByUser['data'])->map(function ($amount, $userId) {
            return [
                'key' => $userId,
                'value' => sprintf('%s %s', $amount['currency'], number_format($amount['amount'], 2))
            ];
        });

        $orderedByCurrency = $this->paginated(orderBy: 'currency', pageName: 'currencyPage');

        $totalRevenuePerCurrency = collect($orderedByCurrency['data'])->map(function ($amount, $currency) {
            return [
                'key' => $currency,
                'value' => sprintf('%s %s', $currency, number_format($amount['amount'], 2))
            ];
        });

        $orderedByDate = $this->paginated(orderBy: 'date', order: 'desc', pageName: 'datePage');

        $totalRevenuePerDay = collect($orderedByDate['data'])->map(function ($amount, $date) {
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

        return Inertia::render('Payments/Dashboard', compact(
            'orderedByUser',
            'sumPerUser',
            'orderedByCurrency',
            'totalRevenuePerCurrency',
            'orderedByDate',
            'totalRevenuePerDay'
        ));
    }

    public function uploadCsv($chunkSize = 100)
    {
        if (request()->hasFile('file')) {
            $csv = request()->file('file')->store('uploads', 'public');
            $csv = Storage::disk('public')->path($csv);

            $separator = ',';
            $rows = [];
            $count = 0;

            $file = fopen($csv, 'r');
            while ($row = fgetcsv($file, null, $separator)) {
                $rows[] = $row;
                $count++;

                if ($count % $chunkSize === 0 && !empty($chunk)) {
                    array_shift($rows);
                    $this->createPayment($rows);

                    $rows = [];
                }
            }
            fclose($file);

            if (!empty($rows)) {
                array_shift($rows);
                $this->createPayment($rows);
            }
        }

        return redirect()->route('payments.index');
    }

    protected function createPayment($rows)
    {
        foreach ($rows as $row) {
            $date = date('Y-m-d', $row[1]);
            $time = date('H:i:s', $row[1]);

            Payment::firstOrCreate([
                'user_id' => $row[0],
                'date' => $date,
                'time' => $time,
                'country' => $row[2],
                'currency' => $row[3],
                'amount_in_cents' => $row[4]
            ]);
        }
    }

    protected function paginated($orderBy, $pageName, $order = 'asc', $perPage = 25, $chunkSize = 100)
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

        $paginated = (new LengthAwarePaginator(
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

        return $paginated;
    }
}
