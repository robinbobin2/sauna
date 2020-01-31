<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    //
    protected $fillable = [
        'date', 'user_id', 'db_user_id', 'room_id'
    ];
}
