<?php

namespace Enpress\Models;

use Enpress\Database\WordpressModel;

class TermRelationship extends WordpressModel
{

    protected $primaryKey = 'term_taxonomy_id';
    protected $table = 'term_relationships';

    protected $guarded = [
        //
    ];

}
