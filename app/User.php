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
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public static $register_validation_rules = [
        'name' => 'required',
        'email' => 'required|email|Unique:users',
        'password' => 'required|min:8',
        'password_confirmation' => 'required|min:8|same:password',
    ];

    public static $login_validation_rules = [
        'email' => 'required|exists:users',
        'password' => 'required',
    ];


    public function socialAccounts(){
        return $this->hasMany(SocialAccount::class);
    }

    public function roles(){
        return $this->belongsToMany('App\Role')->withTimestamps();
    }

    public function hasRole($name){
        foreach($this->roles as $role){
            if($role->name == $name) return true;
        }

        return false;
    }

    public function assignRole($role){
        return $this->roles()->attach($role);
    }

    public function removeRole($role){
        return $this->roles()->detach($role);
    }
}
