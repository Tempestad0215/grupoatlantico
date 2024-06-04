<?php

namespace App\Dtos;

use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Resources\EmployeeDeparment;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use function App\Global\errorHttp;
use function App\Global\successHttp;

class EmployeeDto
{
    // Guardar los datos
    public function store(StoreEmployeeRequest $request): JsonResponse
    {
        try {

            // Guardar los datos
            $data = Employee::create($request->validated());

            // Respuiesta
            $response = successHttp(config("msj.created"),200, $data);

            // Devolver la repuesta
            return $response;

        } catch (\Throwable $th) {
            //throw $th;

            // Respuesta
            $response = errorHttp(config("msj.error"),400, $th->getMessage());

            // Devolver la respuesta
            return $response;
        }
    }

    // Mostrar todo los empleados
    public function show(Request $request): JsonResponse
    {
        try {

            // Sacar los datos del usuarios
            $search = $request->get("search");
            $limit = $request->get("limit",10);

            // Sacar los datos
            $data = Employee::where("status", false)
                ->where(function ($query) use ($search) {
                    $query->where("name","like","%". $search ."%")
                    ->orWhere("email","like","%". $search ."%")
                    ->orWhere("last_name","like","%". $search ."%");
                })
                ->latest()
                ->simplePaginate($limit);

            // Formaterar los datos
            $dataR = EmployeeDeparment::collection($data)->response()->getData(true);

            // Respuesta
            $response = successHttp(config("msj.get"),200, $dataR);

            // Devolver la respuesta
            return $response;


        } catch (\Throwable $th) {
            //throw $th;
            // Respuesta
            $response = errorHttp(config("msj.error"),400, $th->getMessage());

            // Devolver la respuesta
            return $response;
        }
    }


    // Datos para editar
    public function edit(Employee $employee): JsonResponse
    {
        try {
            // Sacar los datos
            $data = Employee::where("status", false)
                ->where("id", $employee->id)
                ->first();

            // Formatear los da tos
            $dataR = new EmployeeDeparment($data);

            // Respuesta
            $response = successHttp(config("msj.get"),200,$dataR);

            // Devolver los datos
            return $response;


        } catch (\Throwable $th) {
            //throw $th;
            // Respuesta
            $response = errorHttp(config("msj.error"),400, $th->getMessage());

            // Devolver la respuesta
            return $response;
        }
    }


    // Actualizar los datos
    public function update(UpdateEmployeeRequest $request, Employee $employee): JsonResponse
    {
        try {

            // Actualizar los datos
            $data = $employee->update($request->validated());


            // Respuesta
            $response = successHttp(config("msj.updated"),200, $data);

            // DEvolver la respuesta
            return $response;

        } catch (\Throwable $th) {
            //throw $th;
            // Respuesta
            $response = errorHttp(config("msj.error"),400, $th->getMessage());

            // Devolver la respuesta
            return $response;
        }
    }

    // Elimianr los datos
    public function destroy(Employee $employee): JsonResponse
    {
        try {

            // Eliminar los datos
            $employee->update(["status"=> false]);

            // Rspuesta
            $response = successHttp(config("msj.deleted"),200);

            // Devolver los datos
            return $response;

        } catch (\Throwable $th) {
            //throw $th;
            // Respuesta
            $response = errorHttp(config("msj.error"),400, $th->getMessage());

            // Devolver la respuesta
            return $response;
        }
    }
}
