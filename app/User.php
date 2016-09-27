<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'api_token'
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

    /**
     * Route notifications for the Slack channel.
     *
     * @return string
     */
    public function routeNotificationForSlack()
    {
        return env('SLACK_URL');
    }
}
