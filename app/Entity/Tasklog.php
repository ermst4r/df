<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class Tasklog extends Model
{
    protected $table = 'task_log';
    protected $fillable = ['task','status','fk_feed_id'];

    public function getfeed()
    {
        return $this->hasOne('App\Entity\Feed','id','fk_feed_id');
    }

}
