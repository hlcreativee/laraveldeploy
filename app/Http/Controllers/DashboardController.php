<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        $monthlyData = DB::table('transaksi')
            ->selectRaw('
                TO_CHAR("InvoiceDate"::timestamp, \'YYYY-MM\') as month,
                SUM("Quantity"::numeric) as qty,
                SUM("Quantity"::numeric * "Price"::numeric) as total
            ')
            ->whereNotNull('InvoiceDate')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $topProductData = DB::table('transaksi')
            ->selectRaw('
                "Description" as product,
                SUM("Quantity"::numeric) as qty
            ')
            ->groupBy('product')
            ->orderByDesc('qty')
            ->first();

        $topProduct = $topProductData->product ?? '-';

        $lastData = $monthlyData
            ->sortBy('month')
            ->values()
            ->slice(-5)
            ->values();

        $prediksi = 0;

        if ($lastData->count() >= 3) {

            $qty = $lastData->pluck('qty')->values()->toArray();

            while (count($qty) < 5) {
                array_unshift($qty, 0);
            }

            $qty = array_slice($qty, -5);

            $last = $lastData->last();

            $payload = [
                "t" => count($monthlyData),
                "month" => date('m', strtotime($last->month . "-01")),
                "year" => date('Y', strtotime($last->month . "-01")),

                "lag1" => $qty[4],
                "lag2" => $qty[3],
                "lag3" => $qty[2],
                "lag4" => $qty[1],
                "lag5" => $qty[0],

                "Description" => "Total"
            ];

            try {

                $response = Http::timeout(10)
                    ->post(
                        'https://tcodersflaskml-production.up.railway.app/predict',
                        $payload
                    );

                $result = $response->json();

                $chart = $result['chart'] ?? [];

                if (isset($chart[0]['qty'])) {
                    $prediksi = $chart[0]['qty'];
                }

            } catch (\Exception $e) {

                $prediksi = 0;
            }
        }

        $labels = $monthlyData
            ->pluck('month')
            ->values()
            ->toArray();

        $qty = $monthlyData
            ->pluck('qty')
            ->values()
            ->toArray();

        if (count($labels) > 0) {

            $lastMonth = end($labels);

            $nextMonth = date(
                'Y-m',
                strtotime($lastMonth . '-01 +1 month')
            );

            $labels[] = $nextMonth;
            $qty[] = $prediksi;
        }

        return view('dashboard', [
            'data' => $monthlyData,
            'labels' => $labels,
            'qty' => $qty,
            'topProduct' => $topProduct,
            'prediksi' => $prediksi
        ]);
    }
}