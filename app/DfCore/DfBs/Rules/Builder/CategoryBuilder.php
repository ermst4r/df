<?php namespace App\DfCore\DfBs\Rules\Builder;
use App\ElasticSearch\ESCategorizeFilter;
use App\ElasticSearch\ESRules;
use App\Entity\CategoryFilter;
use App\Entity\Mongo\Repository\TmpRuleRepository;
use App\Entity\Mongo\TmpRule;
use App\Entity\Repository\CategoryFilterRepository;
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
 * Build the categories and give the feed back
 *
 * Class FeedOperationsBuilder
 * @package App\DfCore\DfBs\Rules\Builder
 * @author: Erwin Nandpersad
 * @website: www.ermmedia.nl
 * @email: erwin@ermmedia.nl
 */
class CategoryBuilder extends AbstractRuleBuilder
{

    private $feed_id;
    private $es_type;
    private $index_name;
    private $filters;
    private $prefilled_products;

    /**
     * @return mixed
     */
    public function getPrefilledProducts()
    {
        return $this->prefilled_products;
    }

    /**
     * @param mixed $prefilled_products
     */
    public function setPrefilledProducts($prefilled_products)
    {
        $this->prefilled_products = $prefilled_products;
    }

    /**
     * @return mixed
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @param mixed $filters
     */
    public function setFilters($filters)
    {
        $this->filters = $filters;
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



    public function buildRule()
    {

        $prefilled_products = $this->getPrefilledProducts();
        $es_category = new ESCategorizeFilter($this->getIndexName(),$this->getEsType());
        $filters = $this->getFilters();

        $product_array = [];
        $total_products = [];
        $filter_counter = 0;
        foreach($filters as $filter) {

            $condition = $filter->category_condition;
            $phrase = $filter->phrase;
            $field = $filter->field;

            $products = $es_category->categoryToES($this->getFeedId(),$phrase,$field,$condition,
                [
                    'category_id'=>$filter->category_id,
                    'category_name'=>$filter->category_name,
                    'category_meta'=>$filter->category_meta,
                ]
            );
            $product_array[$filter_counter] = $products;
            $filter_counter ++;
        }

        for($i=0; $i<count($product_array); $i++) {
            foreach($product_array[$i] as $generated_id => $product) {
                if(isset($total_products[$generated_id])) {
                    continue;
                }

                if(isset($prefilled_products[$generated_id])) {
                    unset($prefilled_products[$generated_id]);
                }
                $total_products[$generated_id] = $product;


            }
        }
        $total_products = $es_category->changeArrayStructure(array_merge($prefilled_products,$total_products));
        return $total_products;

    }


}