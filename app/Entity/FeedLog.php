<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class FeedLog extends Model
{
    protected $table = 'feed_logger';
    protected $fillable = ['log_message','log_type','fk_feed_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function log_messages()
    {
        return $this->belongsTo('App\Entity\Feed','id','fk_feed_id');
    }



}
