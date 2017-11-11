<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class Feed extends Model
{
    protected $table = 'feeds';
    protected $fillable = ['feed_name','feed_url','feed_type','feed_status','fk_store_id','xml_root_node','feed_updated','update_interval','next_update','active','fetched_records','prepend_nodes','prepend_identifier','feed_custom_parser'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function feed_rules()
    {
        return $this->belongsTo('App\Entity\Rule','id','fk_feed_id');
    }


    public function channels()
    {
        return $this->belongsTo('App\Entity\Channelfeed','id','fk_feed_id');
    }



}
