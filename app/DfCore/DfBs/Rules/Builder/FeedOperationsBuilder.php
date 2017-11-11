<?php namespace App\DfCore\DfBs\Rules\Builder;
use App\DfCore\DfBs\Log\LoggerFacade;
use App\ElasticSearch\ESRules;
use App\Entity\CustomMapping;
use App\Entity\Repository\CustomMappingRepository;
use App\Entity\Repository\RuleConditionRepository;
use App\Entity\RuleCondition;

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
 * Build the rules and give us an array back
 *
 * Class FeedOperationsBuilder
 * @package App\DfCore\DfBs\Rules\Builder
 * @author: Erwin Nandpersad
 * @website: www.ermmedia.nl
 * @email: erwin@ermmedia.nl
 */
class FeedOperationsBuilder extends AbstractRuleBuilder
{

    private $feed_id;
    private $load_rules;
    private $list_rules;
    private $es_type;
    private $index_name;
    private $saved_products;

    /**
     * @return mixed
     */
    public function getSavedProducts()
    {
        return $this->saved_products;
    }

    /**
     * @param mixed $saved_products
     */
    public function setSavedProducts($saved_products)
    {
        $this->saved_products = $saved_products;
    }


    /**
     * @return mixed
     */
    public function getEsType()
    {
        return $this->es_type;
    }

    /**
     * @param mixed $es_type
     */
    public function setEsType($es_type)
    {
        $this->es_type = $es_type;
    }

    /**
     * @return mixed
     */
    public function getIndexName()
    {
        return $this->index_name;
    }

    /**
     * @param mixed $index_name
     */
    public function setIndexName($index_name)
    {
        $this->index_name = $index_name;
    }


    /**
     * @return mixed
     */
    public function getListRules()
    {
        return $this->list_rules;
    }

    /**
     * @param mixed $list_rules
     */
    public function setListRules($list_rules)
    {
        $this->list_rules = $list_rules;
    }

    /**
     * @return mixed
     */
    public function getLoadRules()
    {
        return $this->load_rules;
    }

    /**
     * @param mixed $load_rules
     */
    public function setLoadRules($load_rules)
    {
        $this->load_rules = $load_rules;
    }

    /**
     * @return mixed
     */
    public function getFeedId()
    {
        return $this->feed_id;
    }

    /**
     * @param mixed $feed_id
     */
    public function setFeedId($feed_id)
    {
        $this->feed_id = $feed_id;
    }


    /**
     * @return array
     */
    public function buildRule()
    {

        $feed_id = $this->feed_id;
        $index_name = $this->index_name;

        $es_rules = new ESRules($index_name,$this->es_type);
        $ruleCondition = new RuleConditionRepository( new RuleCondition());
        $customMapping = new CustomMappingRepository( new CustomMapping());
        $get_custom_mapping = $customMapping->getCustomMapping($feed_id,'true','fk_feed_id');

        /**
         * Load the register of rules
         */

        $load_rule_from_assoc = $this->getLoadRules();
        $saved_products = $this->getSavedProducts();


        /**
         * Get all the rules from the feed
         */

        $counter = 0;
        foreach($this->getListRules() as $f) {
            $rule_condition = $ruleCondition->getRuleCondition($f->id);
            if(count($rule_condition) == 0 ) {
                continue;
            }
            $get_condition = json_decode($rule_condition[0],true);
            $get_condition = $get_condition['rules'];

            $if_field = (isset($get_condition['if_field'][0]) ?  $get_condition['if_field'][0] : '') ;
            if($if_field == '') {
                LoggerFacade::addAlert("possible system error in the rules @ line 184");
            }
            $has_custom_field = array_search($if_field,$get_custom_mapping) !== false;
            /**
             * If we have a custom field, get the products from the saved products.
             */
            if($has_custom_field) {
                $products[0] = $saved_products;

            } else {
                $products = $es_rules->ifJsonToESQuery($get_condition,$feed_id);
            }




//

            /**
             * Loop through the then rules of this rule
             * And Apply all the rules
             */


            for($i=0; $i<count($products); $i++) {
                foreach($products[$i] as $generated_id => $product) {
                    //Loop through all the then rules and save them to an array!

                    foreach(array_keys($get_condition['then_field']) as $key_fields) {

                        if(isset($saved_products[$generated_id])) {
                            $product = $saved_products[$generated_id];
                        }
                        $then_rule = $get_condition['then_action'][$key_fields];
                        $then_field = $get_condition['then_field'][$key_fields];

                        if(is_null($then_rule) || is_null($then_field)) {

                            continue;
                        }
                        $then_spacing = (isset($get_condition['then_spacing'][$key_fields]) ?  $get_condition['then_spacing'][$key_fields] : []);
                        $then_field_values = $get_condition['then_field_values'][$key_fields];
                        $rule = $load_rule_from_assoc[$then_rule]; // let us load the strategy via an assoc array
                        $product = $rule->handle($product,$then_field,$then_field_values,$then_spacing);

                    }

                    $saved_products[$generated_id] = $product;
                }
            }


            $counter ++;

        }


        return $saved_products;



    }


}