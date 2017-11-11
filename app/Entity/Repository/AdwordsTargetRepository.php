<?php

namespace App\Entity\Repository;


use App\Entity\AdwordsTarget;
use App\Entity\Repository\Contract\iAdwordsTarget;


class AdwordsTargetRepository extends Repository implements iAdwordsTarget
{




    /**
     * @param $fk_adwords_feed_id
     * @return mixed
     */
    public function getAdwordsTarget($fk_adwords_feed_id)
    {
        return $this->model->where('fk_adwords_feed_id',$fk_adwords_feed_id)->first();
    }



    /**
     * @param $data
     * @param int $id
     */
    public function createAdwordsTarget($data, $id=0)
    {
        if($id > 0) {
            $this->model->where('id',$id)->update($data);
        } else {
            $this->model->create($data);
        }


    }


}