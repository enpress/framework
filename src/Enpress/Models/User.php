<?php

namespace Enpress\Models;

use Enpress\Database\ACFieldsTrait;
use Enpress\Database\WordpressModel;

class User extends WordpressModel
{

    use ACFieldsTrait;

    protected $primaryKey = 'ID';
    protected $table = 'users';

    protected $appends = [
        'id',
        'login',
        'nicename',
        'email',
        'URL',
        'registered',
        'activation_key',
        'status'
    ];

    protected $hidden = [
        'user_pass',
        'ID',
        'user_login',
        'user_nicename',
        'user_email',
        'user_url',
        'user_registered',
        'user_activation_key',
        'user_status'
    ];

    protected $dates = [
        'user_registered'
    ];

    public $timestamps = false;

    public function field($name, $default = null)
    {
        return get_field($name, "user_{$this->term_id}") ?: $default;
    }

    public function meta()
    {
        return $this->hasMany(UserMeta::class, 'user_id', 'ID');
    }

    public function setIdAttribute($value)
    {
        $this->ID = $value;
    }

    public function getIdAttribute()
    {
        return $this->ID;
    }

    public function setLoginAttribute($value)
    {
        $this->user_login = $value;
    }

    public function getLoginAttribute()
    {
        return $this->user_login;
    }

    public function setPasswordAttribute($value)
    {
        $this->user_pass = $value;
    }

    public function getPasswordAttribute()
    {
        return $this->user_pass;
    }

    public function setNicenameAttribute($value)
    {
        $this->user_nicename = $value;
    }

    public function getNicenameAttribute()
    {
        return $this->user_nicename;
    }

    public function setEmailAttribute($value)
    {
        $this->user_email = $value;
    }

    public function getEmailAttribute()
    {
        return $this->user_email;
    }

    public function setURLAttribute($value)
    {
        $this->user_url = $value;
    }

    public function getURLAttribute()
    {
        return $this->user_url;
    }

    public function setRegisteredAttribute($value)
    {
        $this->user_registered = $value;
    }

    public function getRegisteredAttribute()
    {
        return $this->user_registered;
    }

    public function setActivationKeyAttribute($value)
    {
        $this->user_activation_key = $value;
    }

    public function getActivationKeyAttribute()
    {
        return $this->user_activation_key;
    }

    public function setStatusAttribute($value)
    {
        $this->user_status = $value;
    }

    public function getStatusAttribute()
    {
        return $this->user_status;
    }

}
