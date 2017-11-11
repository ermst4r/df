<?php

namespace App\Entity\Repository;


use App\Entity\AdwordsConfiguration;
use App\Entity\Repository\Contract\iAdwordsConfiguration;


class AdwordsConfigurationRepository extends Repository implements iAdwordsConfiguration
{

    


    /**
     * @param $data
     * @param int $fk_adwords_feed_id
     * @return mixed
     */
    public function createAdwordsConfiguration($data, $id = 0)
    {
        if($id == 0 ) {
            return $this->model->create($data);
        } else {
            return $this->model->where('id',$id)->update($data);

        }
    }

    /**
     * Has a configuration?
     * @param $fk_adwords_feed_id
     * @return bool
     */
    public function hasAdwordsConfiguration($fk_adwords_feed_id)
    {
        return $this->model->where('fk_adwords_feed_id',$fk_adwords_feed_id)->count() == 1;
    }


    /**
     * @param $fk_adwords_feed_id
     */
    public function getAdwordsConfiguration($fk_adwords_feed_id)
    {
        return $this->model->where('fk_adwords_feed_id',$fk_adwords_feed_id)->first();
    }


}