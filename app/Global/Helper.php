<?php

namespace App\Global;

use App\Models\User;
use Illuminate\Http\JsonResponse;

// Codigo de 6 digito para todo
function generateCode():int {

    // Generar el codigo
    $code = random_int(100000,999999);

    // Tomar los numeros existente
    $exists = User::where("code", $code)->exists();

    // Crear un numero diferente mientra exista uno igual
    while ($exists) {
        // Crear el nuevo numero
        $code = random_int(100000,999999);
    }


    // Devolver los datos
    return $code;
}



// Response json para exito
function successHttp(String $msj, int $code = 200, mixed $data = null):JsonResponse {
    return response()->json([
        "status" => "success",
        "code" => $code,
        "message"=> $msj,
        "data" => $data
    ]);
}


// Reponse con error de todo
function errorHttp(String $msj, int $code = 400, mixed $data = null):JsonResponse {
    return response()->json([
        "status" => "error",
        "code" => $code,
        "message"=> $msj,
        "data" => $data
    ]);
}
