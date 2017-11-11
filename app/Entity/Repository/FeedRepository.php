<?php
/**
 * Created by PhpStorm.
 * User: erm
 * Date: 08-02-17
 * Time: 20:07
 */

namespace App\Entity\Repository;


use App\Entity\Feed;
use App\Entity\Repository\Contract\iFeed;

class FeedRepository extends Repository implements iFeed 
{

   


    /**
     * @param bool $active
     * @return \Illuminate\Support\Collection
     */
    public function getAllActiveFeeds($active = true )
    {
        return $this->model->where('active',$active)->get();
    }


    /**
     * Get the model by id
     * @param $id
     * @return mixed
     */
    public function getFeed($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Add the model to the database
     * @param array $data
     * @param int $id
     * @return int
     */
    public function createFeed($data = array(),$id = 0)
    {
        if($id == 0 ) {
            $feed = $this->model->create($data);
            return $feed->id;
        } else {
            $this->model->find($id)->update($data);
            return $id;
        }
    }


    /**
     * @param $id
     * @return mixed
     */
    public function getFeedRules($id)
    {
        return $this->model->find($id)->feed_rules()->get();
    }


    /**
     * @param $store_id
     * @return mixed
     */
    public function getFeedByStore($store_id,$limit = 0)
    {
        if($limit == 0 ) {
            return $this->model->where('fk_store_id',$store_id)->orderBy('created_at','desc')->get();
        } else {
            return $this->model->where('fk_store_id',$store_id)->orderBy('created_at','desc')->limit($limit)->get();
        }

    }

    /**
     * @param $feed_id
     * @return mixed
     */
    public function removeFeed($feed_id)
    {
        return $this->model->where('id',$feed_id)->delete();
    }





}