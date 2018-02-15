<?php

namespace Enpress\Database;

use Illuminate\Database\Eloquent\Model;

class WordpressModel extends Model
{
    /**
     * Append Wordpress Prefix to Table if Required
     *
     * @return string
     * @throws \Illuminate\Container\EntryNotFoundException
     */
    public function getTable()
    {
        $table = parent::getTable();
        $prefix = app('config')->get('cms.db_prefix');

        return $prefix ? $prefix . $table : $table;
    }
}