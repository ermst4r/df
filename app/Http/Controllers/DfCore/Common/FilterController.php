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


namespace App\Http\Controllers\DfCore\Common;

use App\DfCore\DfBs\Enum\ConditionSelector;
use App\DfCore\DfBs\Enum\UrlKey;
use App\DfCore\DfBs\Import\Mapping\MappedVisibleFieldsFacade;
use App\DfCore\DfBs\Import\Mapping\MappingFactory;
use App\DfCore\DfBs\Import\Remote\RemoteFileService;
use App\DfCore\DfBs\Rules\Wizard\ChannelWizard;
use App\ElasticSearch\ESCategorizeFilter;
use App\Entity\Repository\Contract\iCategory;
use App\Entity\Repository\Contract\iCategoryBol;
use App\Entity\Repository\Contract\iCategoryChannel;
use App\Entity\Repository\Contract\iCategoryFilter;
use App\Entity\Repository\Contract\iFeed;
use App\Http\Controllers\Controller;
use App\Jobs\InsertTmpCategorize;
use App\Jobs\RemoveTmpCategorize;
use Illuminate\Http\Request;
use Route;

class FilterController extends Controller
{
    /**
     * @var iFeed
     */
    private $feed ;

    /**
     * @var
     */
    private $feed_id ;

    /**
     * @var
     */
    private $es_feed;

    /**
     * @var
     */
    private $category;

    /**
     * @var
     */
    private $category_filter;

    /**
     * @var
     */
    private $url_key;

    /**
     * @var
     */
    private $route_name;

    /**
     * @var iCategoryChannel
     */
    private  $category_channel;

    /**
     * @var iCategoryBol
     */
    private $category_bol;

    /**
     * FilterController constructor.
     * @param iFeed $feed
     * @param Request $request
     * @throws \Exception
     */
    public  function __construct(iFeed $feed, Request $request, iCategory $category, iCategoryFilter $category_filter, iCategoryChannel $category_channel, iCategoryBol $category_bol)
    {
        $this->feed = $feed;
        $this->category_filter = $category_filter;

        if(php_sapi_name() != 'cli') {
            $this->feed_id =  (int) $request->route()->parameter('id');
            $this->url_key =  (int) $request->get('url_key');
            $this->route_name = Route::currentRouteName();
        }

        $index_name = createEsIndexName($this->feed_id);
        $this->es_feed = new ESCategorizeFilter($index_name,DFBUILDER_ES_TYPE);
        $this->category = $category;
        $this->category_bol = $category_bol;
        $this->category_channel = $category_channel;



        if($this->feed_id == 0 && php_sapi_name() != 'cli' ) {
            throw new \Exception("Invalid feed id for this controller");
        }

    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function ajax_categories(Request $request)
    {
        $term = $request->get('q');
        $data = [];
        foreach($this->category->getToCategoryByTerm($term) as $results) {
            $data[] = array('id'=>$results->id,'text'=>$results->category_name);
        }
        return  \Illuminate\Support\Facades\Response::json($data);
    }


    /**
     * @param $id
     * @return
     */
    public function browse_uncategorized($id, Request $request)
    {
        $channel_feed_id = $request->get('channel_feed_id');
        $channel_type_id = $request->get('channel_type_id');
        $bol_id = $request->get('bol_id');
        switch($this->url_key) {
            case UrlKey::CHANNEL_FEED:
                $wizard = ChannelWizard::getNavigation($this->url_key,['feed_id'=>$id,'channel_feed_id'=>$channel_feed_id,'channel_type_id'=>$channel_type_id]);
            break;
            case UrlKey::BOL:
                $wizard = ChannelWizard::getNavigation($this->url_key,['feed_id'=>$id,'bol_id'=>$bol_id]);
            break;
        }

        $url_key = $this->url_key;
        $route_name = 'filter.categorize_feed';
        return view('dfcore.filter.browse_uncategorized')->with(compact('id','wizard','route_name','url_key','channel_type_id','channel_feed_id','bol_id','url_key'));
    }

    /**
     * @param $id
     * // @TODO DEBUGGING , it seems we need to add an must not exists query
     */
    public function ajax_browse_uncategorized(Request $request)
    {
        $items_per_page = (int) $request->get('length');
        $start = (int) $request->get('start');
        $channel_feed_id = $request->get('channel_feed_id');
        $channel_type_id = $request->get('channel_type_id');
        $bol_id = $request->get('bol_id');
        $searchArray =  $request->get('search');
        $searchValue =  (isset($searchArray['value']) ? $searchArray['value'] : '');
        /**
         * Get config settingss
         */
        $default_search_field = config('dfbuilder.required_fields_to_map')[0];
        $default_image_field = config('dfbuilder.required_fields_to_map')[1];
        $default_product_url_field = config('dfbuilder.required_fields_to_map')[3];


        switch($this->url_key) {
            case UrlKey::CHANNEL_FEED:
                $es_field_name = es_cat_field_name($channel_feed_id,$this->url_key);
            break;
            case UrlKey::BOL:
                $es_field_name = es_cat_field_name($bol_id,$this->url_key);
            break;
        }


        /**
         * Get mapping  info
         */
        $get_feed = $this->feed->getFeed($this->feed_id);
        $file_name = RemoteFileService::generateSavePath($get_feed->feed_type,$this->feed_id);
        $mapping_info = MappingFactory::setMapping($file_name,$get_feed);
        $fields_from_mapping = MappedVisibleFieldsFacade::getFeedFieldsFromMapping($get_feed,$mapping_info['workable_data']);
        $fields_from_mapping[$default_search_field] = (isset($fields_from_mapping[$default_search_field]) ? $fields_from_mapping[$default_search_field] : '');
        $field_name = 'category_filters.'.$es_field_name;
        $es_response = $this->es_feed->searchCategorizeMapped($this->feed_id,[$field_name => false],$start,$items_per_page,$searchValue,$fields_from_mapping[$default_search_field]);
        $data['recordsTotal'] = $es_response['hits']['total'];
        $total_items_to_show = $es_response['hits']['total'];
        if($es_response['hits']['total'] > 10000) {
            $total_items_to_show = 10000;
        }
        $data['recordsFiltered'] = $total_items_to_show;




        $counter = 0;
        foreach($es_response['hits']['hits'] as $record) {
            foreach($fields_from_mapping as $mapping_key => $mapping_value) {
                if($mapping_key == $default_image_field) {
                    $data['data'][$counter][$mapping_key] = (isset($record['_source'][$mapping_value]) ? "<img src=\"".$record['_source'][$mapping_value]."\" height=\"120\" >" : '');
                } elseif($mapping_key == $default_product_url_field) {
                    $data['data'][$counter][$mapping_key] = (isset($record['_source'][$mapping_value]) ? "<a href=\"".$record['_source'][$mapping_value]."\" target=\"_blank\" > ".$record['_source'][$mapping_value]."</a>" : '');
                } else {
                    $data['data'][$counter][$mapping_key] = (isset($record['_source'][$mapping_value]) ? $record['_source'][$mapping_value] : '');
                }

            }
            $counter++;
        }

        if($es_response['hits']['total'] == 0 ) {
            unset($data);
            $data['draw'] = 0;
            $data['recordsTotal'] = 0;
            $data['recordsFiltered'] = 0;
            $data['data'] = [];
            $data['sEcho'] = 0;
            $data['iTotalRecords'] = 0;
            $data['iTotalDisplayRecords'] = 0;
        }

        return \Illuminate\Support\Facades\Response::json($data);
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function ajax_remove_filter(Request $request)
    {
        $filter_id = (int) $request->get('filter_id');
        $url_key = (int) $request->get('url_key');
        $this->category_filter->createCategoryFilter(['visible'=>false],$filter_id);
        switch($url_key) {
            case UrlKey::CHANNEL_FEED:
                dispatch((new RemoveTmpCategorize($filter_id,$request->get('channel_feed_id'),UrlKey::CHANNEL_FEED))->onQueue('medium'));
            break;

            case UrlKey::BOL:
                dispatch((new RemoveTmpCategorize($filter_id,$request->get('bol_id'),UrlKey::BOL))->onQueue('medium'));
            break;
        }

        return \Illuminate\Support\Facades\Response::json(true);

    }

    /**
     * @param $id
     * @return mixed
     */
    public function categorize_feed($id, Request $request)
    {

        $channel_feed_id = $request->get('channel_feed_id');
        $channel_type_id = $request->get('channel_type_id');
        $bol_id = $request->get('bol_id');


        switch($this->url_key)
        {
            /**
             * Add the url keys for reusability
             */
            case UrlKey::CHANNEL_FEED:
                $wizard = ChannelWizard::getNavigation($this->url_key,
                    [
                        'feed_id'=>$id,
                        'channel_feed_id'=>$request->get('channel_feed_id'),
                        'channel_type_id'=>$request->get('channel_type_id'),
                    ]);
                $category_filter = $this->category_filter->getChannelCategories($channel_feed_id);
            break;


            case UrlKey::BOL:
                $wizard = ChannelWizard::getNavigation($this->url_key,
                    [
                        'feed_id'=>$id,
                        'bol_id'=>$request->get('bol_id')
                    ]);
                $category_filter = $this->category_filter->getBolCategories($bol_id);
            break;
        }



        $route_name = $this->route_name;
        $number_of_records = $this->es_feed->countRecords();
        $category = $this->category->getCategories();
        $fields = $this->es_feed->getEsFields($id);

        $url_key = $this->url_key;
        return view('dfcore.filter.categorize_feed')->with(compact('url_key','id','fields','category_filter','category','number_of_records','wizard','route_name','channel_type_id','channel_feed_id','bol_id'));
    }





    /**
     * @param $id
     * @return mixed
     */
    public function ajax_add_categorize(Request $request, $id)
    {
        $store_id = session('store_id');
        $category = $this->category->getCategories();
        $item_number = (int) $request->get('item_number');
        $fields = $this->es_feed->getEsFields($id);
        return view('dfcore.filter.ajax_add_categorize')->with(compact('fields','item_number','category','store_id'));
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function ajax_save_category_filter(Request $request)
    {


       $data = [
           'fk_feed_id' =>  $this->feed_id,
           'condition' =>   (int) $request->get('selected_condition'),
           'phrase' =>   $request->get('phrase'),
           'fk_category_id' =>  (int) $request->get('to_category'),
           'field'=> $request->get('field')
       ];

       $category_filter = $this->category_filter->createCategoryFilter($data);
       switch ($this->url_key) {
           case UrlKey::CHANNEL_FEED:
               $this->category_channel->createCategoryChannel(['fk_category_filter_id'=>$category_filter->id,'fk_channel_feed_id'=>$request->get('channel_feed_id')]);
               dispatch((new InsertTmpCategorize($category_filter->id,$request->get('channel_feed_id'),UrlKey::CHANNEL_FEED))->onQueue('medium'));
           break;

           case UrlKey::BOL:
               $this->category_bol->createBolCategory(['fk_category_filter_id'=>$category_filter->id,'fk_bol_id'=>$request->get('bol_id')]);
               dispatch((new InsertTmpCategorize($category_filter->id,$request->get('bol_id'),UrlKey::BOL))->onQueue('medium'));
           break;
       }

       return \Illuminate\Support\Facades\Response::json($category_filter);


    }


    /**
     * @return mixed
     */
    public function ajax_calculate_mapped(Request $request)
    {
        $channel_feed_id = $request->get('channel_feed_id');
        $bol_id = $request->get('bol_id');
        $es_field_name = es_cat_field_name($bol_id,UrlKey::CHANNEL_FEED);
        switch($this->url_key) {
            case UrlKey::BOL:
                $es_field_name = es_cat_field_name($bol_id,UrlKey::BOL);
            break;

            case UrlKey::CHANNEL_FEED:
                $es_field_name = es_cat_field_name($channel_feed_id,UrlKey::CHANNEL_FEED);
            break;
        }


        $es_response = $this->es_feed->searchCategorizeMapped($this->feed_id,['category_filters.'.$es_field_name => true]);
        $number_of_documents = $this->es_feed->countRecords();
        $categorize_mapped = (int) $es_response['hits']['total'];
        $percent = $categorize_mapped /  $number_of_documents['count'] * 100;
        $results = [
            'categorize_mapped'=>$categorize_mapped,
            'percent'=>floor($percent),
            'number_of_documents'=>$number_of_documents,
        ];

        return \Illuminate\Support\Facades\Response::json($results);
    }




}