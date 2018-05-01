<?php

namespace Enpress\Models;

use Enpress\Database\WordpressModel;

class TermMeta extends WordpressModel
{

    protected $primaryKey = 'meta_id';
    protected $table = 'termmeta';

    protected $appends = [
        'id',
        'key',
        'value'
    ];

    protected $guarded = [
        'meta_id'
    ];

    public function term()
    {
        return $this->belongsTo(Term::class, 'term_id');
    }

    public function setIdAttribute($value)
    {
        $this->meta_id = $value;
    }

    public function getIdAttribute()
    {
        return $this->meta_id;
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
