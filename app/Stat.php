<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stat extends Model
{
    protected $fillable = [
        'page_id','date','count'
    ];
    public function page()
    {
        return $this->belongsTo('App\Page', 'page_id');
    }
}
