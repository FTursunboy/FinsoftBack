<?php

namespace App\Enums;

enum DocumentHistoryStatuses :string
{
    case CREATED = 'Создан';

    case UPDATED = 'Изменен';

    case DELETED = 'Удален';

    case APPROVED = 'Проведен';

    case RESTORED = 'Восстановлен';

    case UNAPPROVED = 'Отменено проведение';

    case FORCE_DELETED = 'Очищен';


}
