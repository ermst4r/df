<?php

namespace App\Entity\Repository;


use App\Entity\FieldToMap;
use App\Entity\Repository\Contract\iFieldToMap;

class FieldToMapRepository implements iFieldToMap
{

    private $fieldToMap;
    public function __construct(FieldToMap $fieldToMap)
    {
        $this->fieldToMap = $fieldToMap;
    }

    /**
     * Get the feed by id
     * @param $id
     * @return mixed
     */
    public function getField($id=0)
    {
        if($id == 0 ) {
            return  $this->fieldToMap->all();
        } else {
            return $this->fieldToMap->findOrFail($id);
        }

    }

    /**
     * @param array $data
     * @param int $id
     * @return int
     */
    public function createField($data = array(), $id = 0)
    {
        if($id == 0 ) {
            $field_to_map = $this->fieldToMap->create($data);
            return $field_to_map->id;
        } else {
            $this->fieldToMap->find($id)->update($data);
            return $id;
        }
    }


}