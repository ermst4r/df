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

use App\DfCore\DfBs\Enum\RuleConditions;
use App\DfCore\DfBs\Enum\UrlKey;
use App\DfCore\DfBs\Rules\ConditionToHtmlFormType;
use App\DfCore\DfBs\Rules\CustomControlerRegister;
use App\DfCore\DfBs\Rules\Wizard\ChannelWizard;
use App\ElasticSearch\ESRules;
use App\Entity\Repository\Contract\iFeed;
use App\Entity\Repository\Contract\iRule;
use App\Entity\Repository\Contract\iRuleAdwords;
use App\Entity\Repository\Contract\iRuleBol;
use App\Entity\Repository\Contract\iRuleChannel;
use App\Entity\Repository\Contract\iRuleCondition;
use App\Jobs\InsertTmpRule;
use App\Jobs\RemoveTmpRule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Route;


class RulesController extends Controller
{
    /**
     * @var iFeed
     */
    private $feed ;

    /**
     * @var iRule
     */
    private $rule;

    /**
     * @var
     */
    private $get_feed;

    /**
     * @var iRuleCondition
     */
    private $rule_condition;

    /**
     * @var
     */
    private $es_rules;

    /**
     * @var
     */
    private $url_key;

    /**
     * @var
     */
    private $route_name;

    /**
     * @var
     */
    private $rule_channel;


    /**
     * @var
     */
    private $rule_adwords;
    /**
     * @var iRuleBol
     */
    private $rule_bol;


    /**
     * RulesController constructor.
     * @param iFeed $feed
     */
    public  function __construct(iFeed $feed ,iRule $rule, iRuleCondition $rule_condition,
                                 iRuleChannel $rule_channel, iRuleAdwords $rule_adwords,
                                 iRuleBol $rule_bol,
                                 Request $request)
    {
        $this->feed = $feed;
        $this->rule = $rule;
        $this->rule_bol = $rule_bol;
        $this->rule_condition = $rule_condition;
        $this->rule_channel = $rule_channel;
        $this->rule_adwords = $rule_adwords;


        if(php_sapi_name() != 'cli') {
            $id = (  is_null($request->route()->parameter('id')) ? $request->get('id') : $request->route()->parameter('id'));
            $this->get_feed =  $this->feed->getFeed( $id );
            $index_name = createEsIndexName($id);
            $this->es_rules = new ESRules($index_name,DFBUILDER_ES_TYPE);
            $this->url_key =  (int) $request->get('url_key');
            $this->route_name = Route::currentRouteName();
        }

        if(is_null($this->get_feed) && php_sapi_name() != 'cli' ) {
            throw new \Exception("Invalid feed id for this controller");
        }
    }



    /**
     * @param $id
     * @param $rule_id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove_rule($id,$rule_id, Request $request)
    {
        $url_key = $request->get('url_key');
        $this->rule->createRule(['visible'=>false],$rule_id);
        $channel_feed_id = $request->get('channel_feed_id');
        $channel_type_id = $request->get('channel_type_id');
        $adwords_feed_id = $request->get('adwords_feed_id');
        $bol_id = $request->get('bol_id');
        $request->session()->flash('flash_success_noty',trans('messages.rules_overview_rule_lbl2'));

        switch($url_key) {

            case UrlKey::CHANNEL_FEED:
                dispatch((new RemoveTmpRule($rule_id,$channel_feed_id,$url_key,false))->onQueue('medium'));
                return redirect()->route('rules.create_rules',['id'=>$id,'rule_id'=>0,'url_key'=>$url_key,'channel_type_id'=>$channel_type_id,'channel_feed_id'=>$channel_feed_id]);
            break;
            case UrlKey::ADWORDS:
                dispatch((new RemoveTmpRule($rule_id,$adwords_feed_id,$url_key,false))->onQueue('medium'));
                return redirect()->route('rules.create_rules',['id'=>$id,'rule_id'=>0,'url_key'=>$url_key,'adwords_feed_id'=>$adwords_feed_id]);
            break;

            case UrlKey::BOL:
                dispatch((new RemoveTmpRule($rule_id,$bol_id,$url_key,false))->onQueue('medium'));
                return redirect()->route('rules.create_rules',['id'=>$id,'rule_id'=>0,'url_key'=>$url_key,'bol_id'=>$bol_id]);
            break;
        }



    }




    /**
     * Create the rules...
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create_rules($id,$rule_id = 0, Request $request)
    {
        $url_key = $this->url_key;
        $route_name = $this->route_name;
        $rules_dictonary = null;
        $then_rules = null;
        $get_rule = null;
        $adwords_feed_id = 0;
        $channel_feed_id = 0;
        $channel_type_id = 0;
        $bol_id = 0;
        $remove_manual_array = [];
        $create_manual_array = [];
        $is_all_operator = false;
        $feed_id = $this->get_feed->id;
        switch($url_key)  {

            /**
             * Adwords rules
             */
            case UrlKey::ADWORDS:
                $adwords_feed_id = $request->get('adwords_feed_id');
                $wizard = ChannelWizard::getNavigation($this->url_key,['feed_id'=>$id,'fk_adwords_feed_id'=>$adwords_feed_id]);
                $feed_rules =  $this->rule->getAdwordsOrderRules($adwords_feed_id,'asc');
                $remove_manual_array = ['id'=>$id,'rule_id'=>$rule_id,'url_key'=>$url_key,'adwords_feed_id'=>$adwords_feed_id];
                $create_manual_array = ['id'=>$id,'rule_id'=>0,'url_key'=>$url_key,'adwords_feed_id'=>$adwords_feed_id];
            break;


            /**
             * Channel rules
             */
            case UrlKey::CHANNEL_FEED:
                $channel_feed_id = $request->get('channel_feed_id');
                $channel_type_id = $request->get('channel_type_id');
                $wizard = ChannelWizard::getNavigation($this->url_key,['feed_id'=>$id,'channel_feed_id'=>$channel_feed_id,'channel_type_id'=>$channel_type_id]);
                $feed_rules =  $this->rule->getChannelOrdersRules($channel_feed_id,$channel_type_id,'asc');
                $remove_manual_array = ['id'=>$id,'rule_id'=>$rule_id,'url_key'=>$url_key,'channel_type_id'=>$channel_type_id,'channel_feed_id'=>$channel_feed_id];
                $create_manual_array = ['id'=>$id,'rule_id'=>0,'url_key'=>$url_key,'channel_type_id'=>$channel_type_id,'channel_feed_id'=>$channel_feed_id];
            break;

            /**
             * Bol Rules
             */

            case UrlKey::BOL:
                $bol_id = $request->get('bol_id');
                $wizard = ChannelWizard::getNavigation($this->url_key,['feed_id'=>$id,'bol_id'=>$bol_id]);
                $feed_rules =  $this->rule->getBolOrderdRules($bol_id,'asc');
                $remove_manual_array = ['id'=>$id,'bol_id'=>$bol_id,'url_key'=>$url_key,'rule_id'=>$rule_id];
                $create_manual_array = ['id'=>$id,'bol_id'=>$bol_id,'url_key'=>$url_key,'rule_id'=>0];

            break;

        }




        $number_of_records = $this->es_rules->countRecords();

        if($rule_id  > 0 ) {
            $get_rule = $this->rule->getRule($rule_id);
            $rules_dictonary = json_decode($this->rule_condition->getRuleCondition($rule_id,'if')[0],true);
        }
        if(isset($rules_dictonary['rules']['if_field'])) {
            $is_all_operator = array_search('all',$rules_dictonary['rules']['if_field']) !== false;
        }


        $custom_control_loader = CustomControlerRegister::registerCustomControlForJavascript($rules_dictonary);


        $field_names = $this->es_rules->getEsFields($id);

        return view('dfcore.rules.create_rules')->with(
                                    compact('url_key','route_name','wizard','id','rules_dictonary','field_names','rule_id','get_rule','feed_id',
                                     'number_of_records','feed_rules','channel_feed_id','channel_type_id',
                                        'adwords_feed_id','remove_manual_array','create_manual_array',
                                        'is_all_operator','custom_control_loader','bol_id'
                                    ));


    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function ajax_get_form_type(Request $request)
    {
        $rule_type = $request->get('rule_type');
        $feed_id = $this->get_feed->id;
        $type = $request->get('type');
        $conditional_identifier = $request->get('conditional_identifier');

        switch($type) {
            case 'if':
                return \Illuminate\Support\Facades\Response::json(ConditionToHtmlFormType::ifToFormType($rule_type));
            break;

            case 'then':

                /**
                 * Form naming conventions..
                 * Rule_condition_itemnumber_formnumber
                 * De regel conditie, item nummer van javascript, en hoeveel input / text velden de conditie heeft.
                 */
                $then_field_values = [];
                $field_names = $this->es_rules->getEsFields($this->get_feed->id);

                $html = view('dfcore.rules.then_rule_forms.'.$rule_type)->with(compact('conditional_identifier','then_field_values','field_names','feed_id'))->render();
                return \Illuminate\Support\Facades\Response::json(array('html'=>$html));



                break;
        }
    }


    public function ajax_getrule_esfields($feed_id)
    {

        $index_name = createEsIndexName($feed_id);
        $es_rules = new ESRules($index_name,DFBUILDER_ES_TYPE);
        return \Illuminate\Support\Facades\Response::json($es_rules->getEsFields($feed_id));

    }



    private function refreshRule($rule_id_frontend,$id,$urlkey,$update=true)
    {

        if($rule_id_frontend > 0 ) {
            $this->rule->createRule(['visible'=>0],$rule_id_frontend);
            dispatch((new RemoveTmpRule($rule_id_frontend,$id,$urlkey,$update))->onQueue('high'));
        }

    }
    /**
     * Save the rules...
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function post_rules(Request $request)
    {


        $if_field = $request->get('if_field');
        $exists_of_field = $request->get('exists_of_field');
        $if_condition_field = $request->get('if_condition_field');
        $if_condition_textarea = $request->get('if_condition_textarea');
        $if_parent_child = $request->get('if_parent_child');
        $if_main_parent = $request->get('if_main_parent');
        $if_operator = $request->get('if_operator');
        $then_field = $request->get('then_field');
        $then_action = $request->get('then_action');
        $then_meta_fields = $request->get('then_ids');
        $then_spacing = $request->get('then_spacing');
        $rule_id_frontend = (int) $request->get('rule_id');
        $id = (int) $request->get('id');
        $rule_name =  (!is_null($request->get('rule_name') ) ? $request->get('rule_name') : 'Nameless Rule ' );
        $url_key =  (int)$request->get('url_key');

        $channel_feed_id =  (int)$request->get('channel_feed_id');
        $channel_type_id =  (int)$request->get('channel_type_id');
        $adwords_feed_id =  (int)$request->get('adwords_feed_id');
        $bol_id =  (int)$request->get('bol_id');

        /**
         * JSON array for saving...
         */
        $json_array['rules']['if_field'] = $if_field;
        $json_array['rules']['exists_of_field'] = $exists_of_field;
        $json_array['rules']['if_condition_field'] = $if_condition_field;
        $json_array['rules']['if_condition_textarea'] = $if_condition_textarea;
        $json_array['rules']['if_operator'] = $if_operator;
        $json_array['rules']['if_parent_child'] = $if_parent_child;
        $json_array['rules']['if_main_parent'] = $if_main_parent;

        $then_fields = $this->transform_then_meta_fields($then_meta_fields,$request,false);
        $then_spacing = $this->transform_then_meta_fields($then_spacing,$request,true);
        $json_array['rules']['then_field'] = $then_field;
        $json_array['rules']['then_action'] = $then_action;
        $json_array['rules']['then_field_values'] = $then_fields;
        $json_array['rules']['then_spacing'] = $then_spacing;

        $json_array = json_encode($json_array);


        /**
         * When an user updates, create a new rule id and remove the old one..
         */
        if($url_key == UrlKey::ADWORDS) {
            $this->refreshRule($rule_id_frontend,$adwords_feed_id,$url_key,true);
        } elseif($url_key == UrlKey::CHANNEL_FEED) {
            $this->refreshRule($rule_id_frontend,$channel_feed_id,$url_key,true);
        } elseif($url_key == UrlKey::BOL) {
            $this->refreshRule($rule_id_frontend,$bol_id,$url_key,true);
        }


        /**
         * Finally create the new rule..
         */
            $create_rule = $this->rule->createRule(
                [
                    'rule_name'=>$rule_name,
                    'fk_feed_id'=>$id
                ]
            );

            $rule_id = $create_rule->id;

        $this->rule_condition->createRuleCondition(['fk_rule_id'=>$rule_id,'rule_options'=>$json_array]);
        $request->session()->flash('flash_success_noty',trans('messages.rules_if_lbl34'));


            /**
             * Url key settings helper
             */

            switch($url_key) {

                /**
                 * Channel feed
                 */
                case UrlKey::CHANNEL_FEED:
                    $this->rule_channel->createRuleChannel(['fk_channel_feed_id'=>$channel_feed_id,'fk_channel_type_id'=>$channel_type_id,'fk_rule_id'=>$rule_id]);
                    dispatch((new InsertTmpRule($rule_id,$channel_feed_id,$url_key))->onQueue('low'));
                    return redirect()->route('rules.create_rules',['rule_id'=>$rule_id,'id'=>$id,'url_key'=>$url_key,'channel_type_id'=>$channel_type_id,'channel_feed_id'=>$channel_feed_id]);
                break;

                /**
                 * Adwords
                 */
                case UrlKey::ADWORDS:
                    $this->rule_adwords->createAdwordsRule(['fk_adwords_feed_id'=>$adwords_feed_id,'fk_rule_id'=>$rule_id]);
                    dispatch((new InsertTmpRule($rule_id,$adwords_feed_id,$url_key))->onQueue('low'));
                    return redirect()->route('rules.create_rules',['rule_id'=>$rule_id,'id'=>$id,'url_key'=>$url_key,'adwords_feed_id'=>$adwords_feed_id]);
                break;


                case UrlKey::BOL:
                    $this->rule_bol->createBolRule(['fk_bol_id'=>$bol_id,'fk_rule_id'=>$rule_id]);
                    dispatch((new InsertTmpRule($rule_id,$bol_id,$url_key))->onQueue('low'));
                    return redirect()->route('rules.create_rules',['rule_id'=>$rule_id,'id'=>$id,'url_key'=>$url_key,'bol_id'=>$bol_id]);
                break;
            }




    }


    /**
     * Make sure we glue the then fields next to each other..
     * @param $then_meta_fields
     * @return array
     */
    private function transform_then_meta_fields($then_meta_fields, Request $request,$is_spacing=false)
    {
        $return_array = [];
        $then_key = ($is_spacing == false ? 1 : 2);

        if(!is_null($then_meta_fields)) {
            foreach ($then_meta_fields as $key_meta => $meta) {
                $then_identifier_array = explode('_', $meta);
                $return_array[$then_identifier_array[$then_key]][] = $request->get($meta);
                // http://stackoverflow.com/questions/43517760/laravel-5-4-request-get-trims-whitespace-in-input
            }
        }


        return $return_array;
    }


    /**
     * Ajax save the draggable position
     * @param Request $request
     */
    public function ajax_save_draggable($id,Request $request)
    {
        $rule_id = $request->get('rule_id');
        $order = $request->get('order');
        $this->rule->createRule(['order'=>$order],$rule_id);
        return \Illuminate\Support\Facades\Response::json(true);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function ajax_delete_rule(Request $request)
    {
        $channel_feed_id =  (int)$request->get('channel_feed_id');
        $adwords_feed_id =  (int)$request->get('adwords_feed_id');
        $url_key =  (int)$request->get('url_key');
        $rule_id = $request->get('rule_id');
        $bol_id = $request->get('bol_id');


        switch($url_key) {
            case UrlKey::CHANNEL_FEED:
                dispatch((new RemoveTmpRule($rule_id,$channel_feed_id,$url_key,false))->onQueue('medium'));
            break;

            case UrlKey::ADWORDS:
                dispatch((new RemoveTmpRule($rule_id,$adwords_feed_id,$url_key,false))->onQueue('medium'));
            break;

            case UrlKey::BOL:
                dispatch((new RemoveTmpRule($rule_id,$bol_id,$url_key,false))->onQueue('medium'));
            break;
        }


        $this->rule->createRule(['visible'=>false],$rule_id);
        return \Illuminate\Support\Facades\Response::json(true);
    }


    /**
     * @return mixed
     */
    public function ajax_calculate_rules($id, Request $request)
    {

        $channel_feed_id =  (int)$request->get('channel_feed_id');
        $adwords_feed_id =  (int)$request->get('adwords_feed_id');
        $bol_id =  (int)$request->get('bol_id');
        $url_key =  (int)$request->get('url_key');

        switch($url_key) {
            case UrlKey::ADWORDS:
                $rule_field_name = es_cat_field_name($adwords_feed_id,$url_key);
            break;

            case UrlKey::CHANNEL_FEED:
                $rule_field_name = es_cat_field_name($channel_feed_id,$url_key);
            break;

            case UrlKey::BOL:
                $rule_field_name = es_cat_field_name($bol_id,$url_key);
            break;
        }


        $es_response = $this->es_rules->searchRulesMapped($this->get_feed->id,['rule_filters.'.$rule_field_name =>true]);
        $number_of_documents = $this->es_rules->countRecords();
        $rules_mapped = (int) $es_response['hits']['total'];
        $percent = $rules_mapped /  $number_of_documents['count'] * 100;
        $results = [
            'rules_mapped'=>$rules_mapped,
            'percent'=>floor($percent),
            'number_of_documents'=>$number_of_documents,
        ];

        return \Illuminate\Support\Facades\Response::json($results);
    }







}