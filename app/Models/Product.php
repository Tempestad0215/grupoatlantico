<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;


    // Datos a interacturar
    protected $fillable = [
        "name",
        "description",
        "branch_id",
        "stock",
        "status",
    ];

    // Formatear los datos
    public function casts():array
    {
        return [
            "status"=> "boolean",
        ];
    }


    // Relaciones
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branches::class);
    }




}
