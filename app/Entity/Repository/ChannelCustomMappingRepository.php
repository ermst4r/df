<?php


namespace App\Entity\Repository;

use App\Entity\ChannelCustomMapping;
use App\Entity\Repository\Contract\iChannelCustomMapping;
use DB;

/**
 * Class XmlMappingRepository
 * @package App\Entity\Repository
 */
class ChannelCustomMappingRepository  extends Repository implements iChannelCustomMapping
{


    /**
     * @param $data
     */
    public function createCustomChannel($data)
    {
        $this->model->create($data);
    }

    /**]
     * @param $fk_channel_feed_id
     * @param $fk_channel_type_id
     * @return mixed
     */
    public function removeCustomChannelMapping($fk_channel_feed_id,$fk_channel_type_id)
    {

        return $this->model
            ->where('fk_channel_feed_id',$fk_channel_feed_id)
            ->where('fk_channel_type_id',$fk_channel_type_id)
            ->delete();

    }


    /**
     * @param $fk_channel_feed_id
     * @param $fk_channel_type_id
     * @return mixed
     */
    public function getCustomFields($fk_channel_feed_id, $fk_channel_type_id)
    {
        return $this->model
            ->where('fk_channel_feed_id',$fk_channel_feed_id)
            ->where('fk_channel_type_id',$fk_channel_type_id)
            ->get();
    }


}