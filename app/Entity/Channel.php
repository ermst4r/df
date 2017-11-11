<?php

namespace App\Entity;


use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    protected $table = 'channel';
    protected $fillable = ['id','channel_name','channel_image','fk_country_id','channel_export'];
}