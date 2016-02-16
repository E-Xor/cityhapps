<?php

namespace CityHapps;

use Illuminate\Auth\Authenticatable;
//use Illuminate\Database\Eloquent\Model;
use EchoIt\JsonApi\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_USER = 'ROLE_USER';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_name', 'email', 'password', 'role'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     *
     */
    public function categories()
    {
        return $this->belongsToMany('CityHapps\Category', 'user_categories', 'user_id', 'category_id');
    }

    /**
     *
     */
    public function events()
    {
        return $this->belongsToMany('CityHapps\Happ', 'user_event', 'user_id', 'event_id');
    }

    /**
     * @return string User role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param $role
     *
     * @return User $this
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Return true if user role is ROLE_ADMIN
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->getRole() == User::ROLE_ADMIN;
    }
}
