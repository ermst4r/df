<?php


namespace App\Entity\Repository;

use App\Entity\ChannelCountry;
use App\Entity\Repository\Contract\iChannelCountry;

/**
 * Class XmlMappingRepository
 * @package App\Entity\Repository
 */
class ChannelCountryRepository implements iChannelCountry
{
    private $country;
    public function __construct(ChannelCountry $country)
    {
        $this->country = $country;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function createCountry($data)
    {

        $has_entry = $this->country->where('id',$data['id'])->count();
        if($has_entry == 0) {
            $this->country->create($data);
        } else {
            $this->country->where('id',$data['id'])->update($data);
        }
        return $data['id'];

    }


    public function getCountries()
    {
        return $this->country->all();
    }


}