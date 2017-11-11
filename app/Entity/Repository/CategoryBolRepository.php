<?php

namespace App\Entity\Repository;


use App\Entity\CategoryBol;
use App\Entity\CategoryFilter;
use App\Entity\Repository\Contract\iCategoryBol;


class CategoryBolRepository extends Repository implements iCategoryBol
{




    /**
     * @param $data
     */
    public function createBolCategory($data) {

        $this->model->create($data);
    }


}