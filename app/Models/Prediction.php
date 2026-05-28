<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prediction extends Model
{
    use HasFactory;

    protected $table = 'predictions';

    protected $fillable = [
        'product_id',
        'created_by',
        'prediction_date',
        'predicted_quantity',
        'model_used'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}