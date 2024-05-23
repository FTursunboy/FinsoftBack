<?php

namespace App\Enums;

enum DocumentTypes :string
{

        case Purchase = 'Покупка';

        case ReturnProvider = 'Возврат поставщику';

        case SaleToClient = 'Продажа клиенту';

        case ReturnClient = 'Возврат от клиента';

        case ClientOrder = 'Заказ клиента';

        case ProviderOrder = 'Заказ поставщику';

        case Inventory = 'Инвентаризация';

        case Movement = 'Перемещение';


}
