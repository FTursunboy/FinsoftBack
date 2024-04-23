<?php

namespace App\Enums;

enum ResourceTypes :string
{

    case AdminPanel = 'admin_panel';

    case Document = 'documents';

    case PodSystem = 'podsystem';

    case Report = 'report';

    case CashRegister = 'kassa';



}
