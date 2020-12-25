<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable;

    /**
     * The default values
     */
    protected $attributes = [
        'role' => 0,
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Defines role array
     * 
     * @return array
     */
    private function roleArray() 
    {
        return [
            0 => ['name' => 'Member', 'lvl' => 0],
            1 => ['name' => 'Owner', "lvl" => 1],
            2 => ['name' => 'Admin', "lvl" => 2],
        ];
    }

    /**
     * Accessor to make role integer more human friendly
     * 
     * @return string
     */
    public function getRoleAttribute($value) 
    {
        return $this->roleArray()[$value];
    }

    /**
     * Get raw role value, used by middleware to determine permissions
     * 
     * @return int
     */
    public function getRoleLevelAttribute() {
        return $this->attributes['role'];
    }

    /**
     * Mutator to set role
     * 
     * @param  int  $value
     * 
     * @return void
     */
    public function setStatusAttribute($value) 
    {
        $this->attributes['role'] = $value;
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return ['role' => $this->role];
    }

    /**
     * Makes a relationship between user and order
     *
     * @return object
     */
    public function orders()
    {
        return $this->hasMany('App\Order');
    }
}
