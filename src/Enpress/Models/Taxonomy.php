<?php

namespace Enpress\Models;

use Enpress\Database\ACFieldsTrait;
use Enpress\Database\WordpressModel;

class Taxonomy extends WordpressModel
{

    use ACFieldsTrait;

    protected $primaryKey = 'term_taxonomy_id';
    protected $table = 'term_taxonomy';

    protected $appends = ['id'];

    public function term()
    {
        return $this->belongsTo(Term::class, 'term_id', 'term_id');
    }

    public function field($name, $default = null)
    {
        return get_field($name, "term_{$this->term_id}") ?: $default;
    }

    public function getTerm($name)
    {
        return Term::find($this->field($name));
    }

    public function scopeName ($query, $taxonomy)
    {
        return $query->where('taxonomy', $taxonomy);
    }

    public function posts()
    {
        return $this->hasManyThrough(
            Post::class,
            TermRelationship::class,
            'term_taxonomy_id',
            'ID',
            'term_taxonomy_id',
            'object_id'
        );
    }

    public function parent()
    {
        return $this->belongsTo(static::class, 'parent', $this->primaryKey);
    }

    public function children()
    {
        return $this->hasMany(static::class, 'parent', $this->primaryKey);
    }

    public function setIdAttribute($value)
    {
        $this->term_taxonomy_id = $value;
    }

    public function getIdAttribute()
    {
        return $this->term_taxonomy_id;
    }

}
