<?php

namespace App\Entity\Repository;


use App\Entity\FieldToMap;
use App\Entity\Repository\Contract\iFieldToMap;

class FieldToMapRepository extends Repository implements iFieldToMap
{



    /**
     * Get the feed by id
     * @param $id
     * @return mixed
     */
    public function getField($id=0)
    {
        if($id == 0 ) {
            return  $this->model->all();
        } else {
            return $this->model->findOrFail($id);
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
            $field_to_map = $this->model->create($data);
            return $field_to_map->id;
        } else {
            $this->model->find($id)->update($data);
            return $id;
        }
    }


}