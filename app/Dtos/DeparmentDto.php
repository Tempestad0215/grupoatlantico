<?php


namespace App\Dtos;

use App\Http\Requests\StoreDeparmentRequest;
use App\Models\Deparment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use function App\Global\errorHttp;
use function App\Global\successHttp;

class DeparmentDto
{
    // Guardar los datos
    public function store(StoreDeparmentRequest $request): JsonResponse
    {
        try {

            // Guardar los datos
            $data = Deparment::create($request->validated());

            // respuiesta
            $response = successHttp(config("msj.created"),200,$data);

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


    // Actualizar los datos
    public function update(StoreDeparmentRequest $request, Deparment $deparment): JsonResponse
    {
        try {

            // Guardar los datos
            $deparment->update($request->validated());

            // respuiesta
            $response = successHttp(config("msj.updated"),200);

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

    // Devolver todo los departamento resgistrado
    public function show(Request $request): JsonResponse
    {
        try {

            // Sacar los datos a buscar
            $search = $request->get("search");
            $limit = $request->get("limit",10);

            // Sacar los datos
            $data = Deparment::where("status", false)
                ->where(function ($query) use ($search) {
                    $query->where("name","like","%". $search."%")
                    ->orWhere("description","like","%". $search."%");
                })
                ->latest()
                ->simplePaginate($limit);
            // Response
            $response = successHttp(config("msj.get"),200,$data);

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

}
