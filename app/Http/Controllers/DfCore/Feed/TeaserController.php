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


namespace App\Http\Controllers\DfCore\Feed;

use App\DfCore\DfBs\Enum\ConditionSelector;
use App\ElasticSearch\ESCategorizeFilter;
use App\Entity\Repository\Contract\iFeed;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TeaserController extends Controller
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
     * FilterController constructor.
     * @param iFeed $feed
     * @param Request $request
     * @throws \Exception
     */
    public  function __construct(iFeed $feed, Request $request)
    {
        $this->feed = $feed;

        if(php_sapi_name() != 'cli') {
            $this->feed_id =  (int) $request->route()->parameter('id');
        }

        $index_name = createEsIndexName($this->feed_id);
        $this->es_feed = new ESCategorizeFilter($index_name,DFBUILDER_ES_TYPE);



        if($this->feed_id == 0 && php_sapi_name() != 'cli' ) {
            throw new \Exception("Invalid feed id for this controller");
        }

    }



    /**
     * @param Request $request
     * @return mixed
     */
    public function ajax_suggester(Request $request)
    {
        $id = $request->get('id');
        $phrase = $request->get('phrase');
        $field = $request->get('field');
        $results = $this->es_feed->searchAutosuggest($id,$phrase,$field);
        $return_array = [];
        // we don't want to aggegrate dynamic fields in ES
        // so let php remove duplicate values..
        $exists_in_array = [];
        foreach($results['hits']['hits'] as $data) {
            if(!isset($exists_in_array[$data['_source'][$field]] )) {
                $exists_in_array[$data['_source'][$field]] = true;
                $return_array[] = array('name'=>$data['_source'][$field]);
            }
        }
        unset($exists_in_array);


        return \Illuminate\Support\Facades\Response::json($return_array);
    }






    /**
     * Show us the result when someone types in the categorize field.
     * @param Request $request
     */
    public function ajax_categorize_teaser(Request $request)
    {

        $selected_condition = $request->get('selected_condition');
        $field = $request->get('field');
        $phrase = $request->get('phrase');

        switch ($selected_condition) {
            case ConditionSelector::CONTAIN;
            case ConditionSelector::NOT_CONTAIN;
            case ConditionSelector::IS_NOT_EMPTY;
            case ConditionSelector::IS_EMPTY;
            case ConditionSelector::IS_REGEXP;
            case ConditionSelector::NOT_REGEXP;
            case ConditionSelector::EQUALS;
            case ConditionSelector::NOT_EQUALS;
            case ConditionSelector::GT;
            case ConditionSelector::GT_EQ;
            case ConditionSelector::LT;
            case ConditionSelector::LT_EQ;
            case ConditionSelector::RANGE;
                $results = $this->es_feed->categorizeSearchOperations($this->feed_id, $phrase, $field, $selected_condition);
                return \Illuminate\Support\Facades\Response::json($results['hits']['total']);
            break;



        }


    }





}