<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Entrance extends Model
{
    use HasFactory;




    protected $fillable = [
        "order",
        "status",

    ];



    // Formatear los datos
    public function casts():array
    {
        return [
            "order"=> "json",
            "status"=> "boolean",
        ];

    }



    // Relaciones
    public function comment():MorphOne
    {
        return $this->morphOne(Comment::class,"commentable");
    }


}
