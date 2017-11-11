<?php

namespace App\Entity;


use Illuminate\Database\Eloquent\Model;

class AdwordsConfiguration extends Model
{
    protected $table = 'adwords_configuration';
    protected $fillable = ['campaign_name','adgroup_name','cpc','daily_budget','fk_adwords_feed_id','existing_campaign','live','live_option'];
}