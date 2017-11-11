<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class Revision extends Model
{
    protected $table = 'revision_channel';
    protected $fillable = ['fk_feed_id','generated_id','revision_type','revision_field_name','revision_new_content','fk_channel_feed_id','fk_channel_type_id'];


}
