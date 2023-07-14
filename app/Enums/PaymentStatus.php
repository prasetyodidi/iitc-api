<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case Invalid = 'INVALID';
    case Pending = 'PENDING';
    case Valid = 'VALID';
}
