<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Hash;
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
        'fname', 'lname', 'email', 'password', 'gender'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    //mutators for Attributes
    public function setFnameAttribute($value){
      $this->attributes['fname'] = mb_strtolower($value);
    }
    public function setLnameAttribute($value){
      $this->attributes['lname'] = mb_strtolower($value);
    }
    public function setEmailAttribute($value){
      $this->attributes['email'] = mb_strtolower($value);
    }
    public function setPasswordAttribute($value){
      $this->attributes['password'] = Hash::make($value);
    }
    public function setGenderAttribute($value){
      $this->attributes['gender'] = ($value == 'male') ? 1 : 0;
    }
    public function getFnameAttribute($value){
      return ucfirst($value);
    }
    public function getLnameAttribute($value){
      return ucfirst($value);
    }
    public function getEmailAttribute($value){
      return $value;
    }
    public function getGenderAttribute($value){
      return ($value == 1) ? 'male' : 'female';
    }
}
