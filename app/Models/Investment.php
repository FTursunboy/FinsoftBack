<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{

    protected $fillable = ['date','model_id','currency_sum','currency_id','counterparty_id','counterparty_agreement_id','type', 'sum'];

}
