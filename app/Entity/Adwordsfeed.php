<?php

namespace App\Entity;


use Illuminate\Database\Eloquent\Model;

class Adwordsfeed extends Model
{
    protected $table = 'adwords_feed';
    protected $fillable = ['name','fk_feed_id','adwords_account_id','updating','adwords_api_message','update_interval','next_update','active'];
}