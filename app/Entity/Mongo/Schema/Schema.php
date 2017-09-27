<?php

namespace App\Entity\Mongo\Schema;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DfSchema extends Migration
{
    protected $connection ='mongodb';

    /**
     * Set an index on the mongo db field.
     * You will use this function if you not wish to use migrations to set indexes on the mongo db
     * @usage add an array of fields to create indexes. If you need an compound index, add an array as value
     * @param array $fields
     * @param array collection
     */
    public function setIndexes($fields = array(),$collection)
    {
        Schema::connection($this->connection)->table($collection, function(Blueprint $collection) use ($fields)
        {
            foreach($fields as $indexes) {
                $collection->index($indexes);
            }
        });

    }
}