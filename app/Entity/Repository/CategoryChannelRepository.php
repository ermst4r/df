<?php

namespace App\Entity\Repository;


use App\Entity\CategoryChannel;
use App\Entity\CategoryFilter;
use App\Entity\Repository\Contract\iCategoryChannel;


class CategoryChannelRepository implements iCategoryChannel
{

    private $category_channel;

    /**
     * CategoryFilterRepository constructor.
     * @param CategoryFilter $categoryFilter
     */
    public function __construct(CategoryChannel $category_channel)
    {
        $this->category_channel = $category_channel;
    }


    public function createCategoryChannel($data)
    {
        $this->category_channel->create($data);
    }


}