<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feed extends Model
{
  use HasFactory;

  protected $fillable = ['favicon', 'rss_url', 'base_url', 'title'];

  public function articles()
  {
    return $this->hasMany(Article::class);
  }
}
