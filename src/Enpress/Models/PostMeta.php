<?php

namespace Enpress\Models;

use Enpress\Database\WordpressModel;

class PostMeta extends WordpressModel
{

    protected $primaryKey = 'meta_id';
    protected $table = 'postmeta';

    protected $appends = [
        'id',
        'key',
        'value'
    ];

    protected $hidden = [
        'meta_id',
        'post_id',
        'meta_key',
        'meta_value'
    ];

    protected $guarded = [
        'meta_id'
    ];

    public $timestamps = false;

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
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
