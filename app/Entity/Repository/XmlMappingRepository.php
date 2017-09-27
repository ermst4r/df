<?php


namespace App\Entity\Repository;

use App\Entity\Repository\Contract\iXmlMapping;
use App\Entity\Xmlmapping;

/**
 * Class XmlMappingRepository
 * @package App\Entity\Repository
 */
class XmlMappingRepository implements iXmlMapping
{
    private $xmlMapping;
    public function __construct(Xmlmapping $xmlMapping)
    {
        $this->xmlMapping = $xmlMapping;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getXmlMapping($id)
    {
        return $this->xmlMapping->findOrFail($id);
    }

    /**
     * @param array $data
     * @param int $id
     * @return int
     */
    public function createXmlMapping($data = array())
    {

        foreach($data as $rows) {
            $this->xmlMapping->create($rows);
        }

        return count($data);

    }

    /**
     * @param $feed_id
     * @return bool
     */
    public function isMapped($feed_id)
    {
        return $this->xmlMapping->where('fk_feed_id',$feed_id)->count() >= 1;
    }



    /**
     * @param $feed_id
     * @return mixed
     */
    public function getPlainMappedFields($feed_id)
    {
        $array =  $this->xmlMapping->where('fk_feed_id','=',$feed_id)->pluck('xml_map_name','mapped_xml_name');
        $return_array = [];

        foreach($array as $key=>$values) {
            $return_array[$values][$key] = true;
        }

        return $return_array;
    }

    /**
     * @param $id
     * @return mixed
     */
    public  function removeMapping($id)
    {
        return $this->xmlMapping->where('fk_feed_id','=',$id)->delete();
    }
}