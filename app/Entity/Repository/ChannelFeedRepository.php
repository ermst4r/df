<?php


namespace App\Entity\Repository;

use App\Entity\ChannelFeed;
use App\Entity\Repository\Contract\iChannelFeed;
use DB;
/**
 * Class XmlMappingRepository
 * @package App\Entity\Repository
 */
class ChannelFeedRepository implements iChannelFeed
{
    /**
     * @var ChannelFeed
     */
    private $channel_feed;

    /**
     * ChannelFeedRepository constructor.
     * @param ChannelFeed $channel_feed
     */
    public function __construct(ChannelFeed $channel_feed)
    {
        $this->channel_feed = $channel_feed;
    }


    /**
     * @param bool $active
     * @param int $from_feed
     * @return mixed
     */
    public function getActiveChannels($active= true,$from_feed = 0)
    {
        if($from_feed > 0 ) {
            return $this->channel_feed->where('active',$active)->where('fk_feed_id',$from_feed)->get();
        } else {
            return $this->channel_feed->where('active',$active)->get();
        }
      

    }

    public function getActiveChannelsFromFeed($feed_id)
    {
        return $this->channel_feed->where('fk_feed_id',$feed_id)->orderBy('created_at','desc')->get();
    }


    /**
     * @param $store_id
     * @return mixed
     */
    public function getCompleteChannelDetails($store_id)
    {
        $table = $this->channel_feed->getTable();
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
            $channel_feed = $this->channel_feed->create($data);
            return $channel_feed->id;
        } else {

            $this->channel_feed->where('id',$id)->update($data);
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
            return $this->channel_feed->find($id);
        } else {
            return $this->channel_feed->all();
        }
    }


    /**
     * @param $id
     */
    public function removeChannelFeed($id)
    {

        $this->channel_feed->where('id',$id)->delete();
    }


}