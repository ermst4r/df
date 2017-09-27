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
use App\ElasticSearch\ESCategorizeFilter;
use App\Entity\CategoryFilter;
use App\Entity\Repository\CategoryFilterRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RemoveTmpCategorize implements ShouldQueue
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



        $category_filter  = new CategoryFilterRepository(new CategoryFilter());
        $es_cat_field_name = es_cat_field_name($this->component_identifier,$this->url_key);
        $get_category_filter = $category_filter->getCategoryFilter($this->category_filter_id);
        $index_name = createEsIndexName($get_category_filter->fk_feed_id);
        $es_feed = new ESCategorizeFilter($index_name,DFBUILDER_ES_TYPE);
        $results = $es_feed->getResultsFromCatIds($this->category_filter_id);
        $scanned_results = $es_feed->scrollThroughResults($results['_scroll_id'],'1m');
        $merged_results[]  = array_merge($results['hits']['hits'],$scanned_results);
        switch($this->url_key) {
            case UrlKey::CHANNEL_FEED:
                $current_ids = $category_filter->getCatIdsFromChannel($this->component_identifier);
            break;

            case UrlKey::BOL:
                $current_ids = $category_filter->getCatIdsFromBol($this->component_identifier);
            break;
        }


        foreach($merged_results[0] as $values) {
            $cat_ids_array = $values['_source']['cat_ids'];
            $entry_found = array_search($this->category_filter_id, $cat_ids_array);
            if ($entry_found !== false) {
                unset($cat_ids_array[$entry_found]);
            }
            $new_cat_id = [];
            foreach($cat_ids_array as $catids) {
                $new_cat_id[] = $catids;
            }
            $values['_source']['cat_ids'] = $new_cat_id;

            /**
             * Search for this specific feed if there are still ids availble...
             */
            $is_entry_active = false;
            foreach($current_ids as $current_id) {
                if(in_array($current_id,$new_cat_id) !== false) {
                    $is_entry_active = true;
                    break;
                }
            }

            $values['_source']['category_filters'][$es_cat_field_name] = $is_entry_active;
            $es_feed->updateDocument($values['_id'], $values['_source']);
        }

        $category_filter->deleteFilter($this->category_filter_id);
        event(new \App\Events\CatFilterProcessed($get_category_filter->fk_feed_id));
    }






}
