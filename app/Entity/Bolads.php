<?php

namespace App\Entity;


use Illuminate\Database\Eloquent\Model;

class Bolads extends Model
{
    protected $table = 'bol_ads';
    protected $fillable = ['fk_bol_id','fk_feed_id','price','ean','title','fullfilment','condition','delivery_code','stock','description','reference_code'];


    public function getfeed()
    {
        return $this->hasOne('App\Entity\Feed','id','fk_feed_id');
    }

}