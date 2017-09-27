<?php

namespace App\Entity\Repository;


use App\Entity\AdwordsTarget;
use App\Entity\Repository\Contract\iAdwordsTarget;


class AdwordsTargetRepository implements iAdwordsTarget
{

    private $adwords_target;

    /**
     * AdwordsTargetRepository constructor.
     * @param AdwordsTarget $adwords_target
     */
    public function __construct(AdwordsTarget $adwords_target)
    {
        $this->adwords_target = $adwords_target;
    }


    /**
     * @param $fk_adwords_feed_id
     * @return mixed
     */
    public function getAdwordsTarget($fk_adwords_feed_id)
    {
        return $this->adwords_target->where('fk_adwords_feed_id',$fk_adwords_feed_id)->first();
    }



    /**
     * @param $data
     * @param int $id
     */
    public function createAdwordsTarget($data, $id=0)
    {
        if($id > 0) {
            $this->adwords_target->where('id',$id)->update($data);
        } else {
            $this->adwords_target->create($data);
        }


    }


}