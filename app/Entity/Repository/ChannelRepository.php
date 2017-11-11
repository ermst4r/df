<?php


namespace App\Entity\Repository;

use App\Entity\Channel;
use App\Entity\Repository\Contract\iChannel;

/**
 * Class XmlMappingRepository
 * @package App\Entity\Repository
 */
class ChannelRepository extends Repository implements iChannel
{
    

    public function getChannel($channel_id)
    {
        return $this->model->find($channel_id);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function createChannel($data)
    {

        $has_entry = $this->model->where('id',$data['id'])->count();
        if($has_entry == 0) {
            $this->model->create($data);
        } else {
            $this->model->where('id',$data['id'])->update($data);
        }
        return $data['id'];
    }

    /**
     * @param $fk_country_id
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */

    public function getChannelByCountry($fk_country_id)
    {
        return $this->model->where('fk_country_id',$fk_country_id)->get();
    }


}