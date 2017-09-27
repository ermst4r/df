<?php

namespace App\Entity;
use Illuminate\Database\Eloquent\SoftDeletes;


use Illuminate\Database\Eloquent\Model;

class CategoryFilter extends Model
{
    protected $table = 'category_filter';
    protected $fillable = ['field','condition','phrase','fk_category_id','fk_feed_id','visible'];

    public function getcategory()
    {
        return $this->hasOne('App\Entity\Category','id','fk_category_id');
    }


    public function getfeed()
    {
        return $this->hasOne('App\Entity\Feed','id','fk_feed');
    }
}