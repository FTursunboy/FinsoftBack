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

    case OtherIncomes = 'Прочие доходы';

    case OtherParishes = 'Прочие приходы';

    case ReturnToClient = 'Возврат клиенту';

    case Replenishment = 'Пополнение с P/C';

    case ReturnInvestment = 'Возврат вложение';

    case SendingToAnotherCashRegister = 'Отправка на другую кассы';

    case CreditPayment = 'Оплата кредита';

    case ReturnToProvider = 'Возврат поставщику';

    case AdvancePaymentToAccountant = 'Оплата аванс подотчетнику';

    case OtherExpenses = 'Прочие расходы';

    case OtherPayments = 'Прочие оплаты';

    case SalaryPayment = 'Оплата зарплаты';
}
