<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    //
    protected $fillable = [
        'user_id','template_id','title', 'description', 'address'
    ];

    public function stats()
    {
        return $this->hasMany('App\Stat');
    }
}
