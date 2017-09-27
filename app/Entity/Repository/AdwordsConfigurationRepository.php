<?php

namespace App\Entity\Repository;


use App\Entity\AdwordsConfiguration;
use App\Entity\Repository\Contract\iAdwordsConfiguration;


class AdwordsConfigurationRepository implements iAdwordsConfiguration
{

    private $adwords_configuration;

    /**
     * AdwordsConfigurationRepository constructor.
     * @param AdwordsConfiguration $adwords_configuration
     */
    public function __construct(AdwordsConfiguration $adwords_configuration)
    {
        $this->adwords_configuration = $adwords_configuration;
    }


    /**
     * @param $data
     * @param int $fk_adwords_feed_id
     * @return mixed
     */
    public function createAdwordsConfiguration($data, $id = 0)
    {
        if($id == 0 ) {
            return $this->adwords_configuration->create($data);
        } else {
            return $this->adwords_configuration->where('id',$id)->update($data);

        }
    }

    /**
     * Has a configuration?
     * @param $fk_adwords_feed_id
     * @return bool
     */
    public function hasAdwordsConfiguration($fk_adwords_feed_id)
    {
        return $this->adwords_configuration->where('fk_adwords_feed_id',$fk_adwords_feed_id)->count() == 1;
    }


    /**
     * @param $fk_adwords_feed_id
     */
    public function getAdwordsConfiguration($fk_adwords_feed_id)
    {
        return $this->adwords_configuration->where('fk_adwords_feed_id',$fk_adwords_feed_id)->first();
    }


}