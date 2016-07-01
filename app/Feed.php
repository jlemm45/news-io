<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feed extends Model
{
    protected $fillable = [
        'feed_url', 'source', 'icon_name', 'base_url'
    ];

    public function articles()
    {
        return $this->hasMany('\App\Article', 'feed_id');
    }

    private function getFavicon()
    {
       return 'https://www.google.com/s2/favicons?domain='.$this->base_url;
    }

    public function toArray()
    {
        $array = parent::toArray();
        $array['favicon_url'] = $this->getFavicon();
        return $array;
    }

}
