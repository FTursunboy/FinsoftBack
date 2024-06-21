<?php

namespace App\Enums;


enum PlanType :string {

    case Good = 'Товар';

    case Storage = 'Склад';

    case Employee = 'Сотрудник';

    case OperationType = 'Тип операции';

    case OldNewClient = 'Старые и новые клиенты';

}
