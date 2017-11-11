<?php

namespace App\Entity;


use Illuminate\Database\Eloquent\Model;

class CategoryBol extends Model
{
    protected $table = 'category_bol';
    protected $fillable = ['fk_bol_id','fk_category_filter_id'];
    public $timestamps = false;

}