<?php

namespace App\Entity;


use Illuminate\Database\Eloquent\Model;

class AdwordsTarget extends Model
{
    protected $table = 'adwords_target';
    protected $fillable = ['campaign_type','ad_delivery','target_countries','target_languages','fk_adwords_feed_id'];
}