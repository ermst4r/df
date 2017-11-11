<?php

namespace App\Entity;


use Illuminate\Database\Eloquent\Model;

class ChannelFeed extends Model
{
    protected $table = 'channel_feed';
    protected $fillable = ['name','fk_country_id','fk_channel_id','fk_feed_id','fk_channel_type_id','active','update_interval','next_update','updating'];



}