<?php


namespace App\Entity\Repository;

use App\Entity\Channel;
use App\Entity\Repository\Contract\iChannel;

/**
 * Class XmlMappingRepository
 * @package App\Entity\Repository
 */
class ChannelRepository implements iChannel
{
    private $channel;

    /**
     * ChannelRepository constructor.
     * @param Channel $channel
     */
    public function __construct(Channel $channel)
    {
        $this->channel = $channel;
    }

    public function getChannel($channel_id)
    {
        return $this->channel->find($channel_id);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function createChannel($data)
    {

        $has_entry = $this->channel->where('id',$data['id'])->count();
        if($has_entry == 0) {
            $this->channel->create($data);
        } else {
            $this->channel->where('id',$data['id'])->update($data);
        }
        return $data['id'];
    }

    /**
     * @param $fk_country_id
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */

    public function getChannelByCountry($fk_country_id)
    {
        return $this->channel->where('fk_country_id',$fk_country_id)->get();
    }


}