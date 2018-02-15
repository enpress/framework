<?php

namespace Enpress\Models;

use Doctrine\Common\Inflector\Inflector;
use Enpress\Database\ACFieldsTrait;
use Enpress\Database\WordpressModel;

class Term extends WordpressModel
{

    use ACFieldsTrait;

    protected $primaryKey = 'term_id';
    protected $table = 'terms';

    protected $appends = [
        'id',
        'group'
    ];

    public function meta()
    {
        return $this->hasMany(TermMeta::class, 'term_id')
            ->select(['term_id', 'meta_key', 'meta_value']);
    }

    public function field($name, $default = null)
    {
        return get_field($name, "term_{$this->term_id}") ?: $default;
    }

    public function imageUrl($name, $size = 'large', $default = null)
    {
        $image = $this->field($name);
        return isset($image['sizes'][$size]) ? $image['sizes'][$size] : $default;
    }

    public function url()
    {
        return str_replace(get_home_url(), '', get_term_link($this->term_id));
    }

    public function taxonomy()
    {
        return $this->hasMany(Taxonomy::class, 'term_id');
    }

    public function setIdAttribute($value)
    {
        $this->term_id = $value;
    }

    public function getIdAttribute()
    {
        return $this->term_id;
    }

    public function setGroupAttribute($value)
    {
        $this->term_group = $value;
    }

    public function getGroupAttribute()
    {
        return $this->term_group;
    }

    public function getSingularNameAttribute()
    {
        return Inflector::singularize($this->name);
    }

    public function getPluralNameAttribute()
    {
        return Inflector::pluralize($this->name);
    }
}
