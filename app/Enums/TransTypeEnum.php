<?php

namespace App\Enums;

Enum TransTypeEnum: int{
    case ENTRADA = 1;
    case SALIDA = 2;
    case DEFECTUOSO = 3;
    case ACTUALIZACION = 4;
}
