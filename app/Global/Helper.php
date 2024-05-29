<?php

namespace App\Global;

function generateCode():int {

    // Generar el codigo
    $code = random_int(100000,999999);

    // Devolver los datos
    return $code;
}
