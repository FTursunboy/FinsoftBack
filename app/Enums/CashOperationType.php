<?php

namespace App\Enums;


enum CashOperationType :string {

    case ClientPayment = 'Оплата от клиента';

    case WithDraw = 'Снятие с P/C';

    case Investment = 'Вложение';

    case AnotherCashRegister = 'Получение с другой кассы';

    case CreditReceive = 'Получение кредита';

    case ProviderRefund = 'Возврат от поставщика';

    case AccountablePersonRefund = 'Возврат от подотчетника';

    case OtherExpenses = 'Прочие доходы';

    case OtherIncomes = 'Прочие приходы';
}
