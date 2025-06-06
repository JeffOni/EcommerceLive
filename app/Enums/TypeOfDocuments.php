<?php

namespace App\Enums;

enum TypeOfDocuments: int
{
    case CÉDULA = 1;
    case PASSPORT = 2;
    case RUC = 3;
    case DNI = 4;
}
