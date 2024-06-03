<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branches extends Model
{
    use HasFactory;


    // Datos para interacturar
    protected $fillable = [
        "name",
        "description",
        "status"
    ];


    // Formatear los dataos
    public function casts():array
    {
        return [
            "status" => "boolean"
        ];
    }
}
