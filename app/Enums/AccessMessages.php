<?php

namespace App\Enums;

enum AccessMessages :string
{
    case Trial = 'У вас пробная версия. Истекает через ';

    case FewDaysLeft = 'Осталось дней ';

    case NoAccess = 'Нет доступа. Требуется оплата';
}
