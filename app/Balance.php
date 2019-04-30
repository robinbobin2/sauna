<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    protected $fillable = [
        'ammount', 'user_id', 'SignatureValue'
    ];
}
