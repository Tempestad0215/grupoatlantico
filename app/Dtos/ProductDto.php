<?php

namespace App\Dtos;

use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductBranchResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use function App\Global\errorHttp;
use function App\Global\successHttp;

class ProductDto
{
    // Guardar los datos
    public function store(StoreProductRequest $request): JsonResponse
    {
        try {

            // Guardar los datos
            $data = Product::create($request->validated());

            // devolver los datos
            $response = successHttp(config("msj.created"),200,$data);

            // Devolver la respuesta
            return $response;


        } catch (\Throwable $th) {
            //throw $th;
            // Respuesta
            $response = errorHttp(config("msj.error"),200,$th->getMessage());

            // Devolver la respuesta
            return $response;
        }
    }

    // Editar los datos
    public function edit(Product $product): JsonResponse
    {
        try {

            // Tomar los datos
            $data = Product::where("status", false)
                ->where("id", $product->id)
                ->first();

            // Formatear los datos
            $dataR = new ProductBranchResource($data);

            // respuesa
            $response = successHttp(config("msj.get"),200,$dataR);

            // devolver la respuesta
            return $response;


        } catch (\Throwable $th) {
            //throw $th;
            // Respuesta
            $response = errorHttp(config("msj.error"),200,$th->getMessage());

            // Devolver la respuesta
            return $response;
        }
    }

    // Actualizar los datos
    public function update(StoreProductRequest $request, Product $product): JsonResponse
    {
        try {

            // Guardar los datos actualizados
            $product->update($request->validated());

            // Respuesta
            $response = successHttp(config("msj.updated"),200,$product);

            // Devolver la respuesta
            return $response;


        } catch (\Throwable $th) {
            //throw $th;
            // Respuesta
            $response = errorHttp(config("msj.error"),200,$th->getMessage());

            // Devolver la respuesta
            return $response;
        }
    }

    // Busacar todos los datos
    public function show(Request $request): JsonResponse
    {
        try {

            // Sacar los datos a buscar
            $search = $request->get("search");
            $limit = $request->get("limit",10);


            // Buscar los datos
            $data = Product::where("status", false)
                ->where(function ($query) use ($search) {
                    $query->where("name","like","%".$search."%")
                        ->orWhere("description","like","%".$search."%");
                })->latest()
                ->simplePaginate($limit);

            // Formatear los datos
            $dataR = ProductBranchResource::collection($data)->response()->getData(true);

            // REspuiesta
            $response = successHttp(config("msj.get"),200,$dataR);

            // DEvolver los datos
            return $response;


        } catch (\Throwable $th) {
            //throw $th;

            // Respuesta
            $response = errorHttp(config("msj.error"),200,$th->getMessage());

            // Devolver la respuesta
            return $response;
        }
    }

    // Elimianr los datos
    public function destroy(Product $product): JsonResponse
    {
        try {

            // Actualizar los datos
            $product->update([
                "status"=> true
            ]);

            // Respuesta
            $response = successHttp(config("msj.deleted"),200,$product);

            // Devolver los datos
            return $response;

        } catch (\Throwable $th) {
            //throw $th;

            // Respuesta
            $response = errorHttp(config("msj.error"),200,$th->getMessage());

            // Devolver la respuesta
            return $response;
        }
    }

}
