<?php


namespace App\Entity\Repository;

use App\Entity\ChannelCountry;
use App\Entity\Repository\Contract\iChannelCountry;

/**
 * Class XmlMappingRepository
 * @package App\Entity\Repository
 */
class ChannelCountryRepository extends Repository implements iChannelCountry
{


    /**
     * @param $data
     * @return mixed
     */
    public function createCountry($data)
    {

        $has_entry = $this->model->where('id',$data['id'])->count();
        if($has_entry == 0) {
            $this->model->create($data);
        } else {
            $this->model->where('id',$data['id'])->update($data);
        }
        return $data['id'];

    }


    public function getCountries()
    {
        return $this->model->all();
    }


}