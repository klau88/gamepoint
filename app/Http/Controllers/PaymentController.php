<?php

namespace App\Http\Controllers;

use App\Services\ConvertToEuroService;
use App\Services\CsvUploadService;
use App\Services\MapOrderService;
use App\Services\PaginateService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class PaymentController extends Controller
{
    /**
     * @param PaginateService $paginateService
     * @param ConvertToEuroService $convertToEuroService
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function dashboard(PaginateService $paginateService, MapOrderService $mapOrderService, ConvertToEuroService $convertToEuroService): Response
    {
        $orderedByUser = $paginateService->payments(orderBy: 'user_id', pageName: 'usersPage');
        $sumPerUser = $mapOrderService->map($orderedByUser['data']);

        $orderedByCurrency = $paginateService->payments(orderBy: 'currency', pageName: 'currencyPage');
        $totalRevenuePerCurrency = $mapOrderService->map($orderedByCurrency['data']);

        $orderedByDate = $paginateService->payments(orderBy: 'date', pageName: 'datePage', order: 'desc');
        $totalRevenuePerDay = $convertToEuroService->convert($orderedByDate['data']);

        return Inertia::render('Payments/Dashboard', compact(
            'orderedByUser',
            'sumPerUser',
            'orderedByCurrency',
            'totalRevenuePerCurrency',
            'orderedByDate',
            'totalRevenuePerDay'
        ));
    }

    /**
     * @param Request $request
     * @param CsvUploadService $csvUploadService
     * @param int $chunkSize
     * @return RedirectResponse
     */
    public function uploadCsv(Request $request, CsvUploadService $csvUploadService, int $chunkSize = 100): RedirectResponse
    {
        $request->validate(['file' => 'required|file|mimes:csv,txt|max:10240']);

        $csvUploadService->upload($request->file('file'), $chunkSize);

        return redirect()->route('payments.index');
    }
}
