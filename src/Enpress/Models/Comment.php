<?php

namespace Enpress\Models;

use Enpress\Database\WordpressModel;

class Comment extends WordpressModel
{

    const CREATED_AT = 'comment_date';
    const UPDATED_AT = 'comment_date';

    protected $primaryKey = 'comment_ID';
    protected $table = 'comments';

    protected $appends = [
        'id',
        'author',
        'author_email',
        'author_url',
        'author_IP',
        'content',
        'karma',
        'approved',
        'user_agent',
        'type'
    ];

    protected $hidden = [
        'comment_ID',
        'comment_post_ID',
        'comment_parent',
        'user_id',
        'comment_author',
        'comment_author_email',
        'comment_author_url',
        'comment_author_IP',
        'comment_content',
        'comment_karma',
        'comment_approved',
        'comment_agent',
        'comment_type'
    ];

    protected $dates = [
        'comment_date',
        'comment_date_gmt'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class, 'comment_post_ID', 'ID');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'ID');
    }

    public function meta()
    {
        return $this->hasMany(CommentMeta::class, 'comment_id', $this->primaryKey);
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'comment_parent', $this->primaryKey);
    }

    public function children()
    {
        return $this->hasMany(Comment::class, 'comment_parent', $this->primaryKey);
    }

    public function setIdAttribute($value)
    {
        $this->comment_ID = $value;
    }

    public function getIdAttribute()
    {
        return $this->comment_ID;
    }

    public function setAuthorAttribute($value)
    {
        $this->comment_author = $value;
    }

    public function getAuthorAttribute()
    {
        return $this->comment_author;
    }

    public function setAuthorEmailAttribute($value)
    {
        $this->comment_author_email = $value;
    }

    public function getAuthorEmailAttribute()
    {
        return $this->comment_author_email;
    }

    public function setAuthorUrlAttribute($value)
    {
        $this->comment_author_url = $value;
    }

    public function getAuthorUrlAttribute()
    {
        return $this->comment_author_url;
    }

    public function setAuthorIPAttribute($value)
    {
        $this->comment_author_IP = $value;
    }

    public function getAuthorIPAttribute()
    {
        return $this->comment_author_IP;
    }

    public function setContentAttribute($value)
    {
        $this->comment_content = $value;
    }

    public function getContentAttribute()
    {
        return $this->comment_content;
    }

    public function setKarmaAttribute($value)
    {
        $this->comment_karma = $value;
    }

    public function getKarmaAttribute()
    {
        return $this->comment_karma;
    }

    public function setApprovedAttribute($value)
    {
        $this->comment_approved = $value;
    }

    public function getApprovedAttribute()
    {
        return $this->comment_approved;
    }

    public function setUserAgentAttribute($value)
    {
        $this->comment_agent = $value;
    }

    public function getUserAgentAttribute()
    {
        return $this->comment_agent;
    }

    public function setTypeAttribute($value)
    {
        $this->comment_type = $value;
    }

    public function getTypeAttribute()
    {
        return $this->comment_type;
    }

    public function scopeApproved($query)
    {
        return $query->where('comment_approved', true);
    }

    public function scopeNotApproved($query)
    {
        return $query->where('comment_approved', false);
    }

}
