<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Feed;

class Article extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'feed_id', 'article_description', 'article_title', 'article_img'
    ];

    public function feed()
    {
        return $this->belongsTo('\App\Feed');
    }
}
