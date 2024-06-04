<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deparment extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "description",
        "status"
    ];


    public function casts(): array
    {
        return [
            "status" => "boolean"
        ];
    }
}
