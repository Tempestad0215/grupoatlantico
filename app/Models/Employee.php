<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    use HasFactory;


    // Los datos para interactur
    protected $fillable = [
        "name",
        "last_name",
        "email",
        "deparment_id",
        "status"
    ];


    // Modificar los datos
    public function casts()
    {
        return [
            "status"=> "boolean",
        ];
    }



    // Relaciones


    // De muchos a uno
    public function deparment(): BelongsTo
    {
        return $this->belongsTo(Deparment::class);
    }
}
