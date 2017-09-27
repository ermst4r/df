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

class FeedRepository implements iFeed
{

    private $feed;
    public function __construct(Feed $feed)
    {

        $this->feed = $feed;
    }


    /**
     * @param bool $active
     * @return \Illuminate\Support\Collection
     */
    public function getAllActiveFeeds($active = true )
    {
        return $this->feed->where('active',$active)->get();
    }


    /**
     * Get the feed by id
     * @param $id
     * @return mixed
     */
    public function getFeed($id)
    {
        return $this->feed->findOrFail($id);
    }

    /**
     * Add the feed to the database
     * @param array $data
     * @param int $id
     * @return int
     */
    public function createFeed($data = array(),$id = 0)
    {
        if($id == 0 ) {
            $feed = $this->feed->create($data);
            return $feed->id;
        } else {
            $this->feed->find($id)->update($data);
            return $id;
        }
    }


    /**
     * @param $id
     * @return mixed
     */
    public function getFeedRules($id)
    {
        return $this->feed->find($id)->feed_rules()->get();
    }


    /**
     * @param $store_id
     * @return mixed
     */
    public function getFeedByStore($store_id,$limit = 0)
    {
        if($limit == 0 ) {
            return $this->feed->where('fk_store_id',$store_id)->orderBy('created_at','desc')->get();
        } else {
            return $this->feed->where('fk_store_id',$store_id)->orderBy('created_at','desc')->limit($limit)->get();
        }

    }

    /**
     * @param $feed_id
     * @return mixed
     */
    public function removeFeed($feed_id)
    {
        return $this->feed->where('id',$feed_id)->delete();
    }





}