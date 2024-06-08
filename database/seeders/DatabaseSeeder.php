<?php

namespace Database\Seeders;

use App\Models\Branches;
use App\Models\Deparment;
use App\Models\Employee;
use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();
        Branches::factory(10)->create();
        Deparment::factory(10)->create();
        Employee::factory(10)->create();
        Product::factory(10)->create();

        // User::factory()->create([
        //     "name" => "marionil julio",
        //     "email" => "marioguzman140@gmail.com",
        //     "password" => "Password+12",
        // ]);
    }
}
