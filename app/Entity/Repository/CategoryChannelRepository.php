<?php

namespace App\Entity\Repository;


use App\Entity\CategoryChannel;
use App\Entity\CategoryFilter;
use App\Entity\Repository\Contract\iCategoryChannel;


class CategoryChannelRepository extends Repository implements iCategoryChannel
{

    


    public function createCategoryChannel($data)
    {
        $this->model->create($data);
    }


}