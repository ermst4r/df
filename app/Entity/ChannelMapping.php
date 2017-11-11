<?php

namespace App\Entity;


use Illuminate\Database\Eloquent\Model;

class ChannelMapping extends Model
{
    protected $table = 'channel_mapping';
    protected $fillable = ['id','channel_field_name','description','channel_field_type','fk_channel_id','fk_channel_type_id'];
}