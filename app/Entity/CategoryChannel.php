<?php

namespace App\Entity;
use Illuminate\Database\Eloquent\SoftDeletes;


use Illuminate\Database\Eloquent\Model;

class CategoryChannel extends Model
{
    protected $table = 'category_channel';
    protected $fillable = ['fk_category_filter_id','fk_channel_feed_id'];

}