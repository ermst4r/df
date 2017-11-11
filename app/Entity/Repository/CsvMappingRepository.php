<?php
/**
 * Created by PhpStorm.
 * User: erm
 * Date: 08-02-17
 * Time: 20:07
 */

namespace App\Entity\Repository;


use App\Entity\Csvmapping;
use App\Entity\Repository\Contract\iCsvMapping;


class CsvMappingRepository extends Repository implements iCsvMapping
{




    /**
     * @param $id
     * @return mixed
     */
    public function getCsvMapping($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Insert the csv mapping to the db and return the rows affected
     * @param array $data
     * @return int
     */
    public function createCsvMapping($data = array())
    {
        foreach($data as $rows) {
            $this->model->create($rows);
        }

        return count($data);

    }


    /**
     * @param $feed_id
     * @return bool
     */
    public function isMapped($feed_id)
    {
        return $this->model->where('fk_feed_id',$feed_id)->count() >= 1;
    }

    /**
     * @param $feed_id
     * @return mixed
     */
    public function getPlainMappedFields($feed_id)
    {
        $array =  $this->model->where('fk_feed_id','=',$feed_id)->pluck('mapped_field_name','mapped_csv_name');
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
        return $this->model->where('fk_feed_id','=',$id)->delete();
    }

}