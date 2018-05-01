<?php

namespace Enpress\Models;

use Enpress\Database\WordpressModel;

class Option extends WordpressModel
{

    protected $primaryKey = 'option_id';
    protected $table = 'options';

    protected $appends = [
        'id',
        'key',
        'value'
    ];

    protected $hidden = [
        'option_id',
        'option_name',
        'option_value'
    ];

    protected $guarded = [
        'option_id'
    ];

    public $timestamps = false;

    public function setIdAttribute($value)
    {
        $this->option_id = $value;
    }

    public function getIdAttribute()
    {
        return $this->option_id;
    }

    public function setKeyAttribute($value)
    {
        $this->option_name = $value;
    }

    public function getKeyAttribute()
    {
        return $this->option_name;
    }

    public function setValueAttribute($value)
    {
        $this->option_value = $value;
    }

    public function getValueAttribute()
    {
        return $this->option_value;
    }

    public function scopeAutoload($query)
    {
        return $query->where('autoload', 'yes');
    }

    public function scopeNotAutoload($query)
    {
        return $query->where('autoload', 'no');
    }

}
