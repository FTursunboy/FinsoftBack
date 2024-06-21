<?php

namespace App\Enums;


enum PlanType :string {

    case Good = 'Товар';

    case Storage = 'Склад';

    case Employee = 'Сотрудник';

}
