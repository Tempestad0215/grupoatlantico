<?php

namespace App\Http\Controllers;

use App\Dtos\DeparmentDto;
use App\Models\Deparment;
use App\Http\Requests\StoreDeparmentRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

use function App\Global\errorHttp;
use function App\Global\successHttp;

class DeparmentController extends Controller implements HasMiddleware
{

    // Propiedades
    public $deparmentDto;


    // Middleware para controlar
    public static function middleware(): array
    {
        return [
            new Middleware("auth:sanctum")
        ];

    }

    public function __construct()
    {
        $this->deparmentDto = new DeparmentDto();
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDeparmentRequest $request): JsonResponse
    {
        // Devolver los datos
        return $this->deparmentDto->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request): JsonResponse
    {
        // Llamar el metodo para eso
        return $this->deparmentDto->show($request);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Deparment $deparment): JsonResponse
    {
        try {

            // Conseguir los datos
            $data = Deparment::where("status", false)
                ->where("id", $deparment->id)
                ->get();

            // respuesta
            $response = successHttp(config("msj.get"),200, $data);

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

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreDeparmentRequest $request, Deparment $deparment): JsonResponse
    {
        // DEvolver los datos
        return $this->deparmentDto->update($request, $deparment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Deparment $deparment): JsonResponse
    {
        try {

            // Actualizar los datos
            $deparment->update([
                "status"=> true
            ]);

            // Respuesta
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
