<?php

namespace App\Entity\Repository;


use App\Entity\Bolfeed;
use App\Entity\CategoryFilter;
use App\Entity\Repository\Contract\iBolFeed;
use DB;
class BolFeedRepository extends Repository implements iBolFeed
{

   


    /**
     * @param $store_id
     * @return mixed
     */

    public function getCompleteBolFeed($store_id)
    {
        $table = $this->model->getTable();
        return DB::table($table)
            ->join('feeds', $table.'.fk_feed_id', '=', 'feeds.id')
            ->select(
                DB::RAW('
                feeds.feed_name AS feed_name, '.$table.'.name AS name,
                '.$table.'.created_at AS created_at, '.$table.'.id AS bol_id, feeds.id AS feed_id
                '))
            ->where('feeds.fk_store_id','=',$store_id)
            ->orderBy($table.'.created_at','desc')
            ->get();

    }

    /**
     * @param $data
     * @param int $id
     */
    public function createBolFeed($data, $id = 0)
    {
       if($id == 0 ) {
          return $this->model->create($data);
       } else {
          return  $this->model->where('id',$id)->update($data);
       }
    }

    /**
     * @param $fk_feed_id
     * @return mixed
     */
    public function getBolFeed($id,$by_feed=false)
    {
        if(!$by_feed ) {
            return $this->model->findOrFail($id);

        } else {
            return $this->model->where('fk_feed_id',$id)->get();
        }

    }


    /**
     * @param $id
     * @return bool|null
     */
    public function removeBolFeed($id)
    {
        return $this->model->where('id',$id)->delete();
    }

}