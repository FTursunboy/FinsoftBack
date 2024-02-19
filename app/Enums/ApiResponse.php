<?php

namespace App\Enums;

enum ApiResponse :string
{
    case Success = 'Success';
    case Error = 'Something went wrong';
    case NotAccess = 'you dont have access';
    case Created = 'created';
    case Deleted = 'deleted';
}
