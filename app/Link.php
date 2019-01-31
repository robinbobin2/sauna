<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    //
    protected $fillable = [
        'page_id', 'link_address', 'title', 'description', 'color', 'image'
    ];
}
