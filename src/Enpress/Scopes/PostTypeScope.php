<?php

namespace Enpress\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PostTypeScope implements Scope
{
    /**
     * Scope content to specific post type
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @throws \Exception
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if(! defined(get_class($model) . '::POST_TYPE')) {
            throw new \Exception('Post Type Undefined on Model.');
        }

        $builder->where('post_type', $model::POST_TYPE);
    }
}