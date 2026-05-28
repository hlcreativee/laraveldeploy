<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class PrediksiController extends Controller
{
    public function index()
    {
        $monthlyData = DB::table('transaksi')
            ->selectRaw('
                TO_CHAR("InvoiceDate"::timestamp, \'YYYY-MM\') as date,
                SUM("Quantity"::numeric) as qty
            ')
            ->whereNotNull('InvoiceDate')
            ->groupBy('date')
            ->orderBy('date')
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
            ->sortBy('date')
            ->values()
            ->slice(-5)
            ->values();

        $prediksi = 0;

        if ($lastData->count() >= 3) {

            $qty = $lastData->pluck('qty')->toArray();

            while (count($qty) < 5) {
                array_unshift($qty, 0);
            }

            $qty = array_slice($qty, -5);

            $last = $lastData->last();

            $payload = [
                "t" => count($monthlyData),
                "month" => date('m', strtotime($last->date . "-01")),
                "year" => date('Y', strtotime($last->date . "-01")),

                "lag1" => $qty[4],
                "lag2" => $qty[3],
                "lag3" => $qty[2],
                "lag4" => $qty[1],
                "lag5" => $qty[0],

                "Description" => "Total"
            ];

            try {

                $res = Http::timeout(10)
                    ->post(
                        'https://tcodersflaskml-production.up.railway.app/predict',
                        $payload
                    )
                    ->json();

                $prediksi = $res['chart'][0]['qty'] ?? 0;

            } catch (\Exception $e) {

                $prediksi = 0;
            }
        }

        $labels = $monthlyData
            ->pluck('date')
            ->toArray();

        $qty = $monthlyData
            ->pluck('qty')
            ->toArray();

        if (count($labels) > 0) {

            $lastMonth = end($labels);

            $nextMonth = date(
                'Y-m',
                strtotime($lastMonth . "-01 +1 month")
            );

            $labels[] = $nextMonth;
            $qty[] = null;
        }

        $produkData = DB::table('transaksi')
            ->selectRaw('
                "Description" as product,
                SUM("Quantity"::numeric) as qty
            ')
            ->groupBy('product')
            ->get();

        $totalQty = $produkData->sum('qty');

        $prediksiPerProduk = [];

        foreach ($produkData as $p) {

            $ratio = $totalQty > 0
                ? $p->qty / $totalQty
                : 0;

            $prediksiPerProduk[$p->product] = round(
                $prediksi * $ratio
            );
        }

        arsort($prediksiPerProduk);

        return view('prediksi', [
            'data' => $monthlyData,
            'labels' => $labels,
            'qty' => $qty,
            'prediksi' => $prediksi,
            'prediksiProduk' => $prediksiPerProduk,
            'produkData' => $produkData,
            'topProduct' => $topProduct
        ]);
    }
}