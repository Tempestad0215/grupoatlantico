<?php

namespace App\Http\Controllers;

use App\Dtos\EmployeeDto;
use App\Models\Employee;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class EmployeeController extends Controller implements HasMiddleware
{

    public $employeeDto;


    // Middelware
    public static function middleware(): array
    {
        return [
            new Middleware("auth:sanctum")
        ];
    }


    // Constructor
    public function __construct()
    {
        $this->employeeDto = new EmployeeDto();
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeRequest $request)
    {
        // Llamar los datos
        return $this->employeeDto->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request): JsonResponse
    {
        // Devolver los datos
        return $this->employeeDto->show($request);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        // Devolver los datos
        return $this->employeeDto->edit($employee);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        // Devolver los atos
        return $this->employeeDto->update($request, $employee);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {

    }
}
