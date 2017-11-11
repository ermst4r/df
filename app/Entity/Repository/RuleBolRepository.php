<?php

namespace App\Entity\Repository;


use App\Entity\CategoryFilter;
use App\Entity\Repository\Contract\iRuleBol;
use App\Entity\RuleBol;

class RuleBolRepository  extends Repository implements iRuleBol
{



    /**
     * @param $data
     * @param int $id
     * @return mixed
     */
     public function createBolRule($data, $id = 0)
     {
        if($id == 0) {
           return $this->model->create($data);
        } else {
            return $this->model->where('id',$id)->update($data);
        }
     }


}