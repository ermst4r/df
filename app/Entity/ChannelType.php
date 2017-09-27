<?php

namespace App\Entity;


use Illuminate\Database\Eloquent\Model;

class ChannelType extends Model
{
    protected $table = 'channel_type';
    protected $fillable = ['id','channel_type','fk_channel_id'];
}