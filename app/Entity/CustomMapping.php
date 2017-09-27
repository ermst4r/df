<?php

namespace App\Entity;


use Illuminate\Database\Eloquent\Model;

class CustomMapping extends Model
{
    protected $table = 'custom_mapping';
    protected $fillable = ['custom_name','fk_feed_id'];
}