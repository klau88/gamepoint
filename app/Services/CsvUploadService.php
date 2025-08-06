<?php

namespace App\Services;

use App\Models\Payment;
use Illuminate\Support\Facades\Storage;

class CsvUploadService
{
    /**
     * @param $file
     * @param int $chunkSize
     * @return void
     */
    public function upload($file, int $chunkSize = 100): void
    {
        $csv = $file->store('uploads', 'public');
        $csv = Storage::disk('public')->path($csv);

        $separator = ',';
        $rows = [];
        $count = 0;

        $csvFile = fopen($csv, 'r');
        while ($row = fgetcsv($csvFile, null, $separator)) {
            $rows[] = $row;
            $count++;

            if ($count % $chunkSize === 0 && !empty($chunk)) {
                array_shift($rows);
                $this->createPayment($rows);

                $rows = [];
            }
        }
        fclose($csvFile);

        if (!empty($rows)) {
            array_shift($rows);
            $this->createPayment($rows);
        }
    }

    /**
     * @param $rows
     * @return void
     */
    protected function createPayment($rows): void
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
}
