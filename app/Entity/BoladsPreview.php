<?php

namespace App\Entity;


use Illuminate\Database\Eloquent\Model;

class BoladsPreview extends Model
{
    protected $table = 'bol_ads_preview';
    protected $fillable = ['fk_bol_id','fk_feed_id','fk_bol_ad_id','ean','price','title','fullfilment','condition','delivery_code','preview','failed','description','stock','reference_code','in_bol_com'];
}