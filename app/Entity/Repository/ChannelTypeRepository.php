<?php


namespace App\Entity\Repository;

use App\Entity\ChannelType;
use App\Entity\Repository\Contract\iChannelType;

/**
 * Class XmlMappingRepository
 * @package App\Entity\Repository
 */
class ChannelTypeRepository implements iChannelType
{
    private $channel_type;
    public function __construct(ChannelType $channel_type)
    {
        $this->channel_type = $channel_type;
    }


    /**
     * @param $channel_id
     * @return mixed
     */
    public function getChannelTypeByChannel($channel_id)
    {
        return $this->channel_type->where('fk_channel_id',$channel_id)->get();
    }

    /**
     * @param $data
     * @return mixed
     */
    public function createChannelType($data)
    {

        $has_entry = $this->channel_type->where('id',$data['id'])->count();
        if($has_entry == 0) {
            $this->channel_type->create($data);
        } else {
            $this->channel_type->where('id',$data['id'])->update($data);
        }
        return $data['id'];

    }

    public function removeChannelType($channel_id)
    {
        $this->channel_type->where('fk_channel_id',$channel_id)->delete();
    }
}