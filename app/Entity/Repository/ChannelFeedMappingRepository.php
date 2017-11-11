<?php


namespace App\Entity\Repository;

use App\Entity\ChannelFeedMapping;
use App\Entity\Repository\Contract\iChannelFeedMapping;
use DB;

/**
 * Class XmlMappingRepository
 * @package App\Entity\Repository
 */
class ChannelFeedMappingRepository  extends Repository implements iChannelFeedMapping
{
   

    public function createChannelFeedMapping($data)
    {
        $this->model->create($data);
    }

    public function removeChannelFieldMapping($fk_channel_feed_id)
    {
        $this->model->where('fk_channel_feed_id',$fk_channel_feed_id)->delete();

    }


    /**
     * @param $fk_channel_feed_id
     * @param $fk_channel_type_id
     * @return array
     */
    public function spreadSheetDuplicateColumnHelper($fk_channel_feed_id,$fk_channel_type_id)
    {
        $table = $this->model->getTable();
        $rows =  DB::table($table)
            ->join('channel_mapping', $table.'.fk_channel_mapping_id', '=', 'channel_mapping.id')
            ->where($table.'.fk_channel_feed_id',$fk_channel_feed_id)
            ->where($table.'.fk_channel_type_id',$fk_channel_type_id)
            ->get();
        $fields_to_hide = [];
        foreach($rows as $row) {
            if(!isset($results[$row->feed_row_name])) {
                $results[$row->feed_row_name] = 0;
            } else {
                $fields_to_hide[] = $row->channel_field_name;

            }
        }
        return $fields_to_hide;

    }

    /**
     * The mapping template
     * @param $fk_channel_feed_id
     * @param $fk_channel_type_id
     * @return mixed
     */

    public function getMappingTemplate($fk_channel_feed_id,$fk_channel_type_id,$to_array = true)
    {
        $table = $this->model->getTable();
        if($to_array) {
            return DB::table($table)
                ->join('channel_mapping', $table.'.fk_channel_mapping_id', '=', 'channel_mapping.id')
                ->where($table.'.fk_channel_feed_id',$fk_channel_feed_id)
                ->where($table.'.fk_channel_type_id',$fk_channel_type_id)
                ->pluck('channel_mapping.channel_field_name',$table.'.feed_row_name')
                ->toArray();
        } else {
            return DB::table($table)
                ->join('channel_mapping', $table.'.fk_channel_mapping_id', '=', 'channel_mapping.id')
                ->where($table.'.fk_channel_feed_id',$fk_channel_feed_id)
                ->where($table.'.fk_channel_type_id',$fk_channel_type_id)
                ->get();
        }

    }

    /**
     * @param $fk_channel_feed_id
     * @return mixed
     */
    public function getMappedItems($fk_channel_feed_id,$fk_channel_type_id)
    {
        $table = $this->model->getTable();
        return DB::table($table)
            ->join('channel_mapping', $table.'.fk_channel_mapping_id', '=', 'channel_mapping.id')
            ->where($table.'.fk_channel_feed_id',$fk_channel_feed_id)
            ->where($table.'.fk_channel_type_id',$fk_channel_type_id)
            ->pluck($table.'.feed_row_name','channel_mapping.channel_field_name')
            ->toArray();


    }


    /**
     * @param $fk_channel_feed_id
     * @param $fk_channel_type_id
     * @param $feed_row_name
     * @return bool
     */
    public function hasDuplicateFieldName($fk_channel_feed_id,$fk_channel_type_id,$feed_row_name)
    {
        return $this->model
            ->where('fk_channel_feed_id',$fk_channel_feed_id)
            ->where('fk_channel_type_id',$fk_channel_type_id)
            ->where('feed_row_name',$feed_row_name)->count() >= 1;

    }




}