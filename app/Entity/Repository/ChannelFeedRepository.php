<?php


namespace App\Entity\Repository;

use App\Entity\ChannelFeed;
use App\Entity\Repository\Contract\iChannelFeed;
use DB;
/**
 * Class XmlMappingRepository
 * @package App\Entity\Repository
 */
class ChannelFeedRepository extends Repository implements iChannelFeed
{



    /**
     * @param bool $active
     * @param int $from_feed
     * @return mixed
     */
    public function getActiveChannels($active= true,$from_feed = 0)
    {
        if($from_feed > 0 ) {
            return $this->model->where('active',$active)->where('fk_feed_id',$from_feed)->get();
        } else {
            return $this->model->where('active',$active)->get();
        }


    }

    public function getActiveChannelsFromFeed($feed_id)
    {
        return $this->model->where('fk_feed_id',$feed_id)->orderBy('created_at','desc')->get();
    }


    /**
     * @param $store_id
     * @return mixed
     */
    public function getCompleteChannelDetails($store_id)
    {
        $table = $this->model->getTable();
        return DB::table($table)
            ->join('feeds', $table.'.fk_feed_id', '=', 'feeds.id')
            ->join('channel', $table.'.fk_channel_id', '=','channel.id')
            ->select(
                DB::RAW('
                channel.channel_name AS network_name, channel_feed.name AS channel_feed_name, feeds.feed_name AS feed_name, feeds.id AS feed_id, channel_feed.id AS channel_feed_id, channel_feed.created_at AS channel_feed_created
                '))
            ->where('feeds.fk_store_id','=',$store_id)
            ->orderBy($table.'.created_at','desc')
            ->get();

    }

    /**
     * Create the channel feed
     * @param $data
     * @param $id
     * @return mixed
     */
    public function createChannelFeed($data, $id=0)
    {
        if($id == 0 ) {
            $channel_feed = $this->model->create($data);
            return $channel_feed->id;
        } else {

            $this->model->where('id',$id)->update($data);
            return $id;
        }
    }


    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getChannelFeed($id = 0)
    {
        if($id > 0 ) {
            return $this->model->find($id);
        } else {
            return $this->model->all();
        }
    }


    /**
     * @param $id
     */
    public function removeChannelFeed($id)
    {

        $this->model->where('id',$id)->delete();
    }


}