<?php

namespace App\Entity;


use Illuminate\Database\Eloquent\Model;

class AdwordsRevision extends Model
{
    protected $table = 'adwords_revision';
    protected $fillable = ['fk_adwords_feed_id','generated_id','revision_type','revision_field_name','revision_new_content','fk_ads_preview_id'];
}