<?php

namespace Enpress\Models;

use Enpress\Database\ACFieldsTrait;
use Enpress\Database\WordpressModel;

class Post extends WordpressModel
{

    use ACFieldsTrait;

    const CREATED_AT = 'post_date';
    const UPDATED_AT = 'post_modified';

    protected $primaryKey = 'ID';
    protected $table = 'posts';

    protected $appends = [
        'id',
        'title',
        'name',
        'date',
        'content',
        'excerpt',
        'status',
        'password',
        'type',
        'mime'
    ];

    protected $hidden = [
        'ID',
        'post_title',
        'post_name',
        'post_content',
        'post_excerpt',
        'post_status',
        'post_password',
        'post_type',
        'post_mime_type',
        'post_author',
        'post_parent'
    ];

    protected $dates = [
        'post_date',
        'post_date_gmt',
        'post_modified',
        'post_modified_gmt'
    ];

    public function field($name, $default = null)
    {
        return get_field($name, $this->ID) ?: $default;
    }

    public function imageUrl($name, $size = 'large', $default = null)
    {
        $image = $this->field($name);
        return isset($image['sizes'][$size]) ? $image['sizes'][$size] : $default;
    }

    public function featuredImage($size = null)
    {
        return get_the_post_thumbnail_url($this->ID, $size);
    }

    public function url($relative = true)
    {
        return $relative ? str_replace(get_home_url(), '', get_permalink($this->ID)) : get_permalink($this->ID);
    }

    public function content($field = null)
    {
        if ($field) {
            return apply_filters('the_content', $this->field($field));
        } else {
            return apply_filters('the_content', $this->post_content);
        }
    }

    public function excerpt($words = 30, $ellipsis = '...', $field = null)
    {
        if($field) {
            $content = $this->field($field);
        } else {
            $content = $this->post_content;
        }
        return wp_trim_words($content, $words, $ellipsis);
    }

    public function meta()
    {
        return $this->hasMany(PostMeta::Class, 'post_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'comment_post_ID', $this->primaryKey);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'post_author', 'ID');
    }

    public function terms ($taxonomy = null)
    {
        $rel = $this->hasManyThrough(
            Taxonomy::class,
            TermRelationship::class,
            'object_id',
            'term_taxonomy_id'
        )->with('term');

        if (!$taxonomy) {
            return $rel;
        }

        return $rel->get()->filter(function($term) use ($taxonomy){
            return $term->taxonomy == $taxonomy;
        });
    }

    public function categories ()
    {
        return $this->terms()->where('taxonomy', 'category');
    }

    public function author()
    {
        return $this->user();
    }

    public function parent()
    {
        return $this->belongsTo(static::class, 'post_parent', $this->primaryKey);
    }

    public function children()
    {
        return $this->hasMany(static::class, 'post_parent', $this->primaryKey);
    }

    public function setIdAttribute($value)
    {
        $this->attributes['ID'] = $value;
    }

    public function getIdAttribute()
    {
        return $this->attributes['ID'];
    }

    public function setTitleAttribute($value)
    {
        $this->post_title = $value;
    }

    public function getTitleAttribute()
    {
        return $this->post_title;
    }

    public function setNameAttribute($value)
    {
        $this->post_name = $value;
    }

    public function getNameAttribute()
    {
        return $this->post_name;
    }

    public function setDateAttribute($value)
    {
        $this->post_date = $value;
    }

    public function getDateAttribute()
    {
        return $this->post_date;
    }

    public function setContentAttribute($value)
    {
        $this->post_content = $value;
    }

    public function getContentAttribute()
    {
        return $this->post_content;
    }

    public function setExcerptAttribute($value)
    {
        $this->post_excerpt = $value;
    }

    public function getExcerptAttribute()
    {
        return $this->post_excerpt;
    }

    public function setStatusAttribute($value)
    {
        $this->post_status = $value;
    }

    public function getStatusAttribute()
    {
        return $this->post_status;
    }

    public function setPasswordAttribute($value)
    {
        $this->post_password = $value;
    }

    public function getPasswordAttribute()
    {
        return $this->post_password;
    }

    public function setTypeAttribute($value)
    {
        $this->post_type = $value;
    }

    public function getTypeAttribute()
    {
        return $this->post_type;
    }

    public function setMimeAttribute($value)
    {
        $this->post_mime_type = $value;
    }

    public function getMimeAttribute()
    {
        return $this->post_mime_type;
    }

    public function scopePublished($query)
    {
        return $query->where('post_status', 'publish');
    }

    public function scopeDraft($query)
    {
        return $query->where('post_status', 'draft');
    }

    public function scopeCommentsOpen($query)
    {
        return $query->where('comment_status', 'open');
    }

    public function scopeCommentsClosed($query)
    {
        return $query->where('comment_status', 'closed');
    }

    public function scopePingOpen($query)
    {
        return $query->where('ping_status', 'open');
    }

    public function scopePingClosed($query)
    {
        return $query->where('ping_status', 'closed');
    }

    public function scopeStatus($query, $status = 'publish')
    {
        return $query->where('post_status', $status);
    }

    public function scopeType($query, $type = 'post')
    {
        return $query->where('post_type', $type);
    }

}
