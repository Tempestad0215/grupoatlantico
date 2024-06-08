<?php

namespace Database\Factories;

use App\Models\Deparment;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{


    protected $model = Employee::class;


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name,
            'last_name' => fake()->lastName,
            'email'=> fake()->unique()->email,
            'deparment_id' => Deparment::factory()
        ];
    }
}
