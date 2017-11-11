<?php

namespace App\Entity;


use Illuminate\Database\Eloquent\Model;

class Bolfeed extends Model
{
    protected $table = 'bol_feed';
    protected $fillable = ['name','public_key','private_key','fk_feed_id','next_update','update_interval','description','stock','delivery_code'];
}