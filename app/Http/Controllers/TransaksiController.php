<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index()
    {
        $data = DB::table('transaksi')
            ->select(
                'Invoice',
                'StockCode',
                'Description',
                'Quantity',
                'InvoiceDate',
                'Price',
                'CustomerID',
                'Country'
            )
            ->whereNotNull('InvoiceDate')
            ->orderBy('InvoiceDate', 'desc')
            ->paginate(50);

        return view('transaksi', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Invoice' => 'required|numeric',
            'StockCode' => 'required',
            'InvoiceDate' => 'required',
            'Description' => 'required',
            'Quantity' => 'required|numeric',
            'Price' => 'required|numeric',
            'CustomerID' => 'required|numeric',
            'Country' => 'required',
        ]);

       DB::table('transaksi')->insert([
            'Invoice' => $request->Invoice,
            'StockCode' => $request->StockCode,
            'Description' => $request->Description,
            'Quantity' => $request->Quantity,
            'InvoiceDate' => date('Y-m-d H:i:s', strtotime($request->InvoiceDate)),
            'Price' => $request->Price,
            'CustomerID' => $request->CustomerID,
            'Country' => $request->Country,
        ]);

        return redirect()->route('transaksi.index')
            ->with('success', 'Data berhasil ditambahkan');
    }
}