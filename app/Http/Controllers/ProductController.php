<?php

namespace App\Http\Controllers;

use App\Dtos\ProductDto;
use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ProductController extends Controller implements HasMiddleware
{
    // Propiedades
    public $productDto;

    // Middleware de access
    public static function middleware()
    {
        return [
            new Middleware("auth:sanctum")
        ] ;
    }

    // Constructo
    public function __construct()
    {
        $this->productDto = new ProductDto();
    }

    // Guardar los datos
    public function store(StoreProductRequest $request):JsonResponse
    {
        // Guardar y devolver los datos
        return $this->productDto->store($request);
    }

    // Editar el producto seleccionado
    public function edit(Product $product)
    {
        // Llmar el metodo y deolver los datos
        return $this->productDto->edit($product);
    }


    // Actualizar los datos
    public function update(StoreProductRequest $request, Product $product)
    {
        // Llamar los datos y devolver los datos
        return $this->productDto->update($request, $product);
    }


    // Mostrar todos los registros
    public function show(Request $request):JsonResponse
    {
        // Llamar el metodo y devolver los datos
        return $this->productDto->show($request);
    }


    // Eliminar
    public function destroy(Product $product)
    {
        // Llamar el metodo y devolver los datos
        return $this->productDto->destroy($product);
    }
}
