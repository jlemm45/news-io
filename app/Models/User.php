<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Http\UploadedFile;

class User extends Authenticatable
{
  use HasApiTokens, HasFactory, Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = ['name', 'email', 'password', 'photo'];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = ['password', 'remember_token'];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  /**
   * The attributes that should be appended automatically.
   *
   * @var array<string, string>
   */
  protected $appends = ['avatar'];

  public function feeds()
  {
    return $this->belongsToMany(Feed::class);
  }

  public function articles()
  {
    return $this->belongsToMany(Article::class);
  }

  public function setPhotoAttribute($photo)
  {
    if (!$photo) {
      return;
    }

    $this->attributes['photo_path'] =
      $photo instanceof UploadedFile ? $photo->store('users') : $photo;
  }

  public function getAvatarAttribute()
  {
    return '/img/' . $this->photo_path . '?h=50&w=50&fit=crop';
  }
}
