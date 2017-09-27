<?php


namespace App\DfCore\DfBs\Import\Filters;


use App\DfCore\DfBs\Import\Filters\Contract\iFilter;

/**
 * Class ExampleFilter
 * @package App\DfCore\DfBs\Import\Filters
 */
class ExampleFilter implements iFilter
{

    /**
     * An example filter.
     * The variable import data contains the feed row.
     * Over here you can manipulate the entries per feed
     * @param $import_data
     * @return mixed
     */
    public function handle($import_data,$mapped_fields_from_user)
    {
       return $import_data;
    }

}