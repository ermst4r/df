<?php

namespace App\Entity\Repository;


use App\Entity\CategoryBol;
use App\Entity\CategoryFilter;
use App\Entity\Repository\Contract\iCategoryBol;


class CategoryBolRepository implements iCategoryBol
{

    private $category_bol;

    /**
     * CategoryFilterRepository constructor.
     * @param CategoryFilter $categoryFilter
     */
    public function __construct(CategoryBol $category_bol)
    {
        $this->category_bol = $category_bol;
    }

    /**
     * @param $data
     */
    public function createBolCategory($data) {

        $this->category_bol->create($data);
    }


}