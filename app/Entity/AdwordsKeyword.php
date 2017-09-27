<?php

namespace App\Entity;


use Illuminate\Database\Eloquent\Model;

class AdwordsKeyword extends Model
{
    protected $table = 'adwords_keywords';
    protected $fillable = ['keyword','keyword_type','keyword_option','fk_adwords_feed_id','visible'];
}