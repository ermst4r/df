<?php
/**
 *  This file is part of Dfbuilder.
 *
 *     Dfbuilder is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     Dfbuilder is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with Dfbuilder.  If not, see <http://www.gnu.org/licenses/>
 */

namespace App\Jobs;

use App\DfCore\DfBs\Enum\UrlKey;
use App\DfCore\DfBs\Rules\RuleCronjobFacade;
use App\ElasticSearch\ESCategorizeFilter;
use App\Entity\CategoryFilter;
use App\Entity\Repository\CategoryFilterRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class InsertTmpCategorize implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $category_filter_id;
    protected $component_identifier;
    private  $url_key;

    /**
     * Added job for inserting...
     * InsertTmpCategorize constructor.
     * @param $category_filter_id
     */
    public function __construct($category_filter_id,$component_identifier,$url_key)
    {
        $this->category_filter_id = (int) $category_filter_id;
        $this->url_key = (int) $url_key;
        $this->component_identifier =  $component_identifier;

    }

    /**
     * Execute the job.
     * @return void
     */
    public function handle()
    {

        /**
         * Code voor scan...
         */
        $category_filter  = new CategoryFilterRepository(new CategoryFilter());
        $get_category_filter = $category_filter->getCategoryFilter($this->category_filter_id);
        RuleCronjobFacade::insertFilters($this->category_filter_id,$this->component_identifier,$this->url_key,$get_category_filter,$get_category_filter->condition);
        event(new \App\Events\CatFilterProcessed($get_category_filter->fk_feed_id));


    }



}
