<?php namespace App\DfCore\DfBs\Rules\Builder;
use App\DfCore\DfBs\Enum\ESIndexTypes;
use App\DfCore\DfBs\Rules\RuleStrategy\RegisterStrategy;
use App\Entity\Repository\RuleRepository;
use App\Entity\Rule;

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

class FeedOperationDirector
{

    private $builder;
    public function __construct()
    {
        $this->builder = new FeedOperationsBuilder();
    }

    /**
     * Build channel rules
     * @param $feed_id
     * @param $channel_feed_id
     * @param array $products
     * @param $es_type
     * @param int $rule_id
     * @param string $index_type
     * @return array
     */
    public function buildChannelRules($feed_id,$channel_feed_id,$products=[],$es_type, $rule_id = 0, $index_type = ESIndexTypes::TMP)
    {
        $rules = new RuleRepository( new Rule());
        $registerStrategy = new RegisterStrategy();
        $index_name = createEsIndexName($feed_id,$index_type);
        $this->builder->setFeedId($feed_id);
        $this->builder->setSavedProducts($products);
        $this->builder->setLoadRules($registerStrategy->loadRules());
        $loaded_rules = $rules->getChannelOrdersRules($channel_feed_id,0,'asc');

        if($rule_id > 0) {
            $loaded_rules = $rules->getRule($rule_id,true);
        }

        $this->builder->setListRules($loaded_rules);
        $this->builder->setIndexName($index_name);
        $this->builder->setEsType($es_type);

        return $this->builder->buildRule();
    }


    /**
     * @param $adwords_feed_id
     * @param $products
     * @param $rule_id
     */
    public function buildAdwordRules($adwords_feed_id,$products,$rule_id=0,$feed_id, $index_type = ESIndexTypes::TMP,$es_type=DFBUILDER_ES_TYPE)
    {
        $rules = new RuleRepository( new Rule());
        $registerStrategy = new RegisterStrategy();
        $index_name = createEsIndexName($feed_id,$index_type);


        $this->builder->setFeedId($feed_id);
        $this->builder->setSavedProducts($products);
        $this->builder->setLoadRules($registerStrategy->loadRules());
        $loaded_rules = $rules->getAdwordsOrderRules($adwords_feed_id,'asc');
        if($rule_id > 0) {
            $loaded_rules = $rules->getRule($rule_id,true);
        }
        $this->builder->setListRules($loaded_rules);
        $this->builder->setIndexName($index_name);
        $this->builder->setEsType($es_type);
        return $this->builder->buildRule();

    }


    /**
     * @param $bol_id
     * @param $products
     * @param int $rule_id
     * @param $feed_id
     * @param string $index_type
     * @param string $es_type
     * @return array
     */
    public function buildBolRules($bol_id,$products,$rule_id=0,$feed_id, $index_type = ESIndexTypes::TMP,$es_type=DFBUILDER_ES_TYPE)
    {
        $rules = new RuleRepository( new Rule());
        $registerStrategy = new RegisterStrategy();
        $index_name = createEsIndexName($feed_id,$index_type);


        $this->builder->setFeedId($feed_id);
        $this->builder->setSavedProducts($products);
        $this->builder->setLoadRules($registerStrategy->loadRules());
        $loaded_rules = $rules->getBolOrderdRules($bol_id,'asc');
        if($rule_id > 0) {
            $loaded_rules = $rules->getRule($rule_id,true);
        }
        $this->builder->setListRules($loaded_rules);
        $this->builder->setIndexName($index_name);
        $this->builder->setEsType($es_type);
        return $this->builder->buildRule();


    }




}