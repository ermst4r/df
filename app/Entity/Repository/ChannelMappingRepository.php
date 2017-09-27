<?php


namespace App\Entity\Repository;

use App\Entity\ChannelMapping;
use App\Entity\Repository\Contract\iChannelMapping;

/**
 * Class XmlMappingRepository
 * @package App\Entity\Repository
 */
class ChannelMappingRepository implements iChannelMapping
{
    private $channel_mapping;
    public function __construct(ChannelMapping $channel_mapping)
    {
        $this->channel_mapping = $channel_mapping;
    }

    /**
     * Update or create...
     * @param $data
     * @return mixed
     */
   public function createChannelMapping($data)
   {
       $has_entry = $this->channel_mapping->where('id',$data['id'])->count();
       if($has_entry == 0) {
            $this->channel_mapping->create($data);
       } else {
           $this->channel_mapping->where('id',$data['id'])->update($data);
       }
       return $data['id'];

   }

    /**
     * @param $fk_channel_id
     * @return mixed
     */
   public function getChannelMappings($fk_channel_id,$fk_channel_type_id,$pluck_value=false)
   {
       if($pluck_value) {
           return $this->channel_mapping

               ->where('fk_channel_id',$fk_channel_id)
               ->where('fk_channel_type_id',$fk_channel_type_id)
               ->orderBy('channel_field_type','asc')
               ->pluck('channel_field_name')
               ->toArray();
       } else {
           return $this->channel_mapping
               ->where('fk_channel_id',$fk_channel_id)
               ->where('fk_channel_type_id',$fk_channel_type_id)
               ->orderBy('channel_field_type','asc')->get();
       }

   }




}