<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'Invoice',
        'StockCode',
        'Description',
        'Quantity',
        'InvoiceDate',
        'Price',
        'CustomerID',
        'Country'
    ];
}