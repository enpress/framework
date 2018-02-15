<?php

namespace Enpress\Models;

use Enpress\Database\WordpressModel;

class Link extends WordpressModel
{

    protected $primaryKey = 'link_id';
    protected $table = 'links';

    const CREATED_AT = 'link_updated';
    const UPDATED_AT = 'link_updated';

    public function scopeVisible($query)
    {
        return $query->where('link_visible', 'Y');
    }

    public function scopeHidden($query)
    {
        return $query->where('link_visible', 'N');
    }

}
