<?php

namespace App\Enums;

enum DocumentStatuses :string
{

        case Approved = 'Проведен';

        case Unapproved = 'Не проведен';


}
