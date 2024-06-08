<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTrans extends Model
{
    use HasFactory;


    // Datos para interacturar
    protected $fillable = [
        'product_id',
        'type',
        'quantity'
    ];
}
