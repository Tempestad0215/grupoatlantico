<?php

namespace App\Http\Controllers;

use App\Models\Branches;
use App\Http\Requests\StoreBranchesRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

use function App\Global\errorHttp;
use function App\Global\successHttp;

class BranchesController extends Controller implements HasMiddleware
{



    // Middleware para controllar todo
    public static function middleware()
    {
        return [
            new Middleware(["auth:sanctum","verified"])
        ];
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBranchesRequest $request):JsonResponse
    {
        try {

            // Guardar los datos validado
            $data = Branches::create($request->validated());

            // Crear la respuesta
            $response = successHttp(config("msj.created"),200, $data);

            // devolver la respuesta
            return $response;

        } catch (\Throwable $th) {
            //throw $th;
            // Respuesta
            $response = errorHttp(config("msj.error"),400,$th->getMessage());

            // Devolver la respuesta
            throw new HttpResponseException($response);
        }
    }


    // Para editar los datos
    public function edit(Branches $branch) :JsonResponse
    {
        try {

            if($branch->status === true)
            {
                // Crear la respuesta
                $response = errorHttp("Esta registro no esta disponible por el momento", 404);

                // Devolver la respuesta
                return $response;
            }


            // Crear la respuesta
            $response = successHttp(config("msj.get"),200, $branch);

            // devolver la respuesta
            return $response;


        } catch (\Throwable $th) {
            //throw $th;
            // Respuesta
            $response = errorHttp(config("msj.error"),400,$th->getMessage());

            // Devolver la respuesta
            throw new HttpResponseException($response);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Request $request):JsonResponse
    {
        try {

            // El parametro para la busqueda
            $search = $request->get("search");
            $limit = $request->get("limit");

            // Buscar los datos
            $data = Branches::where("status", false)
                ->where(function ($query) use ($search) {
                    $query->where("name","like","%". $search ."%")
                    ->orWhere("description","like","%". $search ."%");
                })
                ->latest()
                ->simplePaginate($limit);

            // Deolver los datos
            $response = successHttp(config("msj.get"),200, $data);

            // Devolver la respuesta
            return $response;


        } catch (\Throwable $th) {
            //throw $th;

            // Respuesta
            $response = errorHttp(config("msj.error"),400,$th->getMessage());

            // Devolver la respuesta
            throw new HttpResponseException($response);
        }

    }



    /**
     * Update the specified resource in storage.
     */
    public function update(StoreBranchesRequest $request, Branches $branch): JsonResponse
    {
        try {

            // Actualizar los datos validado
            $branch->update($request->validated());

            // Crear la respuesta
            $response = successHttp(config("msj.updated"),200);

            // Devolver la respuesta
            return $response;

        } catch (\Throwable $th) {
            //throw $th;

            // Respuesta
            $response = errorHttp(config("msj.error"),400,$th->getMessage());

            // Devolver la respuesta
            throw new HttpResponseException($response);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branches $branch): JsonResponse
    {
        try {

            // Actualizar el status de los datos
            $branch->update([
                "status" => true
            ]);

            // Crear la respuesta
            $response = successHttp(config("msj.deleted"),200);

            // Devolver la respuesta
            return $response;

        } catch (\Throwable $th) {
            //throw $th;

            // Respuesta
            $response = errorHttp(config("msj.error"),400,$th->getMessage());

            // Devolver la respuesta
            throw new HttpResponseException($response);
        }
    }
}
