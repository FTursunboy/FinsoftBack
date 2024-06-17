<?php

namespace App\Enums;

enum ServiceTypes: string
{
    case Sale = 'Покупка';

    case Return = 'Возврат';
}
