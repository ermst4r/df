<?php

namespace App\Entity;


use Illuminate\Database\Eloquent\Model;

class ChannelCustomMapping extends Model
{
    protected $table = 'channel_custom_mapping';
    protected $fillable = ['fk_channel_feed_id','fk_feed_id','fk_channel_type_id','custom_field_name','field_name'];
}