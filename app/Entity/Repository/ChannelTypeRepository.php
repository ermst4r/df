<?php


namespace App\Entity\Repository;

use App\Entity\ChannelType;
use App\Entity\Repository\Contract\iChannelType;

/**
 * Class XmlMappingRepository
 * @package App\Entity\Repository
 */
class ChannelTypeRepository extends Repository implements iChannelType
{


    /**
     * @param $channel_id
     * @return mixed
     */
    public function getChannelTypeByChannel($channel_id)
    {
        return $this->model->where('fk_channel_id',$channel_id)->get();
    }

    /**
     * @param $data
     * @return mixed
     */
    public function createChannelType($data)
    {

        $has_entry = $this->model->where('id',$data['id'])->count();
        if($has_entry == 0) {
            $this->model->create($data);
        } else {
            $this->model->where('id',$data['id'])->update($data);
        }
        return $data['id'];

    }

    public function removeChannelType($channel_id)
    {
        $this->model->where('fk_channel_id',$channel_id)->delete();
    }
}