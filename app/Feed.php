<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feed extends Model
{
    protected $fillable = [
        'feed_url', 'source', 'icon_name'
    ];

    public function articles()
    {
        return $this->hasMany('\App\Article', 'feed_id');
    }

}
