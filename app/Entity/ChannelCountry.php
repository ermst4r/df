<?php

namespace App\Entity;


use Illuminate\Database\Eloquent\Model;

class ChannelCountry extends Model
{
    protected $table = 'channel_country';
    protected $fillable = ['id','country'];
}