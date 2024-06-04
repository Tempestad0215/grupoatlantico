<?php

namespace App\Http\Controllers;

use App\Dtos\ProductDto;
use App\Http\Requests\StoreProductRequest;
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
}
