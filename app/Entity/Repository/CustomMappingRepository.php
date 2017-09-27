<?php
/**
 * Created by PhpStorm.
 * User: erm
 * Date: 08-02-17
 * Time: 20:07
 */

namespace App\Entity\Repository;


use App\Entity\Csvmapping;
use App\Entity\CustomMapping;
use App\Entity\Dflogger;
use App\Entity\Repository\Contract\iCsvMapping;
use App\Entity\Repository\Contract\iCustomMapping;
use App\Entity\Repository\Contract\iDflogger;


class CustomMappingRepository implements iCustomMapping
{

    private $custom_mapping;

    /**
     * CustomMappingRepository constructor.
     * @param CustomMapping $customMapping
     */
    public function __construct(CustomMapping $customMapping)
    {
        $this->custom_mapping = $customMapping;
    }

    /**
     * @param $fk_feed_id
     */
    public function removeCustomMapping($fk_feed_id)
    {
        $this->custom_mapping->where('fk_feed_id',$fk_feed_id)->delete();
    }

    /**
     * @param $data
     * @param int $id
     * @return $this|bool|\Illuminate\Database\Eloquent\Model
     */
    public function createCustomMapping($data, $id=0)
    {
        if($id == 0 ) {
            return  $this->custom_mapping->create($data);
        } else {
            return $this->custom_mapping->where('id',$id)->update($data);
        }

    }

    /**
     * @param $id
     * @param bool $pluck
     * @param string $col
     * @return array|\Illuminate\Support\Collection
     */
    public function getCustomMapping($id, $pluck = false,$col ='')
    {
        if(!$pluck) {
            return $this->custom_mapping->where($col,$id)->get();
        } else {
            return $this->custom_mapping->where($col,$id)->pluck('custom_name')->toArray();
        }
    }


}