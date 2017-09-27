<?php

namespace App\Entity;


use Illuminate\Database\Eloquent\Model;

class AdwordsAd extends Model
{
    protected $table = 'adwords_ads';
    protected $fillable = ['headline_1','headline_2','description','path_1','path_2','final_url','is_backup_template','parent_id','fk_adwords_feed_id'];
}