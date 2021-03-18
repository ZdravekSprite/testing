<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
  use HasFactory, Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'email',
    'password',
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  /**
   * Get the users days.
   */
  public function days()
  {
    return $this->hasMany(Day::class);
  }

  /**
   * The roles that belong to the user.
   */
  public function roles()
  {
    return $this->belongsToMany(Role::class);
  }

  public function hasAnyRoles($roles)
  {
    return null !== $this->roles()->whereIn('name', $roles)->first();
  }

  public function hasAnyRole($role)
  {
    return null !== $this->roles()->where('name', $role)->first();
  }

  public function messages()
  {
    return $this->hasMany(Chat::class);
  }
}
