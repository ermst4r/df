<?php

namespace App\Entity\Repository;


use App\Entity\Adwordsfeed;
use App\Entity\Repository\Contract\iAdwordsfeed;
use DB;


class AdwordsfeedRepository extends Repository implements iAdwordsfeed
{

   







    /**
     * @param $store_id
     * @return mixed
     */
    public function getCompleteAdwordsFeeds($store_id)
    {
        $table = $this->model->getTable();
        return $this->model
            ->join('feeds', $table.'.fk_feed_id', '=', 'feeds.id')
            ->select(
                DB::RAW('
                feeds.feed_name AS feed_name, '.$table.'.name AS adwords_name,'.$table.'.adwords_account_id AS adwords_account_id,'.$table.'.created_at AS created_at, '.$table.'.id AS adwords_id, feeds.id AS feed_id
                '))
            ->where('feeds.fk_store_id','=',$store_id)
            ->orderBy($table.'.created_at','desc')
            ->get();

    }


    /**
     * @param int $active
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAllAdwordsFeeds($active = true)
    {
        return $this->model->where('active',$active)->get();
    }

    /**
     * @param $id
     */
    public function removeAdwordsFeed($id)
    {
       $this->model->where('id',$id)->delete();
    }



    /**
     * @param $feed_id
     * @return mixed
     */
    public function getAdwordsFeedFromFeedId($feed_id)
    {
        return $this->model->where('fk_feed_id',$feed_id)->get();
    }
    /**
     *
     */
    public function getAdwordsFeed($id)
    {
       return $this->model->find($id);
    }

    /**
     * @param $data
     * @param int $id
     * @return mixed
     */
    public function createAdwordsFeed($data,$id=0)
    {
        if($id == 0 ) {
            return $this->model->create($data);
        } else {
             $this->model->where('id',$id)->update($data);
            return $id;
        }

    }


}