<?php

namespace App\Dtos;

use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

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
            $product = Product::where("status", false)
                ->where("id", $product->id)
                ->first();

            // respuesa
            $response = successHttp(config("msj.get"),200,$product);

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
}
