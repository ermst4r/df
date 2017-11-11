<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    protected $table = 'rules';
    protected $fillable = ['rule_name','fk_feed_id','order','visible'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function feeds()
    {
        return $this->belongsTo('App\Entity\Feed','fk_feed_id','id');
    }

}
