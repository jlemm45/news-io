<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token', 'updated_at', 'created_at'
    ];

    public function feeds()
    {
        return $this->belongsToMany('App\Feed');
    }

    public function articles()
    {
        return $this->belongsToMany('App\Article');
    }

    /**
     * Remove all feeds attached to user
     */
    public function removeFeeds() {
        $this->feeds()->detach();
    }

    public function unAddedFeeds() {
        return Feed::whereNotIn('id', $this->feeds->modelKeys());
    }
}
