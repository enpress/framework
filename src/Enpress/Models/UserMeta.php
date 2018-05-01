<?php

namespace Enpress\Models;

use Enpress\Database\WordpressModel;

class UserMeta extends WordpressModel
{

    protected $primaryKey = 'umeta_id';
    protected $table = 'usermeta';

    protected $appends = [
        'id',
        'key',
        'value'
    ];

    protected $hidden = [
        'umeta_id',
        'user_id',
        'meta_key',
        'meta_value'
    ];

    protected $guarded = [
        'umeta_id'
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function setIdAttribute($value)
    {
        $this->umeta_id = $value;
    }

    public function getIdAttribute()
    {
        return $this->umeta_id;
    }

    public function setKeyAttribute($value)
    {
        $this->meta_key = $value;
    }

    public function getKeyAttribute()
    {
        return $this->meta_key;
    }

    public function setValueAttribute($value)
    {
        $this->meta_value = $value;
    }

    public function getValueAttribute()
    {
        return $this->meta_value;
    }

}
