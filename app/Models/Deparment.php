<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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



    // Relaciones




    public function employee(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

}
