<?php

namespace App\Enums;

enum Operations :string
{

    case Create = 'create';

    case Update = 'update';

    case Delete = 'delete';

    case Read = 'read';

    case Approve = 'approve';

    case Reject = 'reject';


}
