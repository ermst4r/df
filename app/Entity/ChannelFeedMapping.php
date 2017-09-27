<?php

namespace App\Entity;


use Illuminate\Database\Eloquent\Model;

class ChannelFeedMapping extends Model
{
    protected $table = 'channel_feed_mapping';
    protected $fillable = ['fk_channel_id','fk_feed_id','fk_channel_mapping_id','feed_row_name','fk_channel_feed_id','fk_channel_type_id'];
}