<?php namespace App\DfCore\DfBs\Rules\Builder;
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
use App\Entity\CategoryFilter;
use App\Entity\Repository\CategoryFilterRepository;


/**
 * Class RuleDirector
 * @package App\DfCore\DfBs\Rules\Builder
 * @author: Erwin Nandpersad
 * @website: www.ermmedia.nl
 * @email: erwin@ermmedia.nl
 */
class CategoryDirector
{

    private $builder;

    public function __construct()
    {
        $this->builder = new CategoryBuilder();
    }

    public function buildCategoryRule($feed_id,$channel_feed_id,$all_products,$es_type)
    {
        $categoryFilter = new CategoryFilterRepository(new CategoryFilter());
        $filters = $categoryFilter->getChannelCategories($channel_feed_id);
        $this->builder->setFeedId($feed_id);
        $this->builder->setIndexName(createEsIndexName($feed_id));
        $this->builder->setFilters($filters);
        $this->builder->setPrefilledProducts($all_products);
        $this->builder->setEsType($es_type);
        return $this->builder->buildRule();

    }
}


