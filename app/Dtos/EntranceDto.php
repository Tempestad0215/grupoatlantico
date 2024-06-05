<?php


namespace App\Dtos;

use App\Http\Requests\StoreEntranceRequest;
use App\Models\Entrance;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

use function App\Global\errorHttp;
use function App\Global\successHttp;

class EntranceDto
{
    // Guardar lo sdatos
    public function store(StoreEntranceRequest $request): JsonResponse
    {
        try {

            return  DB::transaction(function () use ($request) {
                // Guardar los datos
                $entrance = Entrance::create($request->validated());

                // Crear el comentario
                $entrance->comment()->create([
                    'info' => $request->info
                ]);

                // Respuesta
                $response = successHttp(config("msj.created"),200, $entrance);

                // Devolver los datos
                return $response;

            });


        } catch (\Throwable $th) {
            //throw $th;
            // Respuesta
            $response = errorHttp(config("msj.error"),400, $th->getMessage());

            // Devolver la respuesta
            return $response;
        }
    }

}
