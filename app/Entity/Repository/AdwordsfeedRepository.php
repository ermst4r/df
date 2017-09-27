<?php

namespace App\Entity\Repository;


use App\Entity\Adwordsfeed;
use App\Entity\Repository\Contract\iAdwordsfeed;
use DB;


class AdwordsfeedRepository implements iAdwordsfeed
{

    private $adwords_feed;







    /**
     * @param $store_id
     * @return mixed
     */
    public function getCompleteAdwordsFeeds($store_id)
    {
        $table = $this->adwords_feed->getTable();
        return DB::table($table)
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
        return $this->adwords_feed->where('active',$active)->get();
    }

    /**
     * @param $id
     */
    public function removeAdwordsFeed($id)
    {
       $this->adwords_feed->where('id',$id)->delete();
    }

    /**
     * AdwordsfeedRepository constructor.
     * @param Adwordsfeed $adwords_feed
     */
    public function __construct(Adwordsfeed $adwords_feed)
    {
        $this->adwords_feed = $adwords_feed;
    }


    /**
     * @param $feed_id
     * @return mixed
     */
    public function getAdwordsFeedFromFeedId($feed_id)
    {
        return $this->adwords_feed->where('fk_feed_id',$feed_id)->get();
    }
    /**
     *
     */
    public function getAdwordsFeed($id)
    {
       return $this->adwords_feed->find($id);
    }

    /**
     * @param $data
     * @param int $id
     * @return int
     */
    public function createAdwordsFeed($data,$id=0)
    {
        if($id == 0 ) {
            return $this->adwords_feed->create($data);
        } else {
             $this->adwords_feed->where('id',$id)->update($data);
            return $id;
        }

    }


}