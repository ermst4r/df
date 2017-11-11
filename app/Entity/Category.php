<?php

namespace App\Entity;


use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';
    protected $fillable = ['category_name','category_meta','type'];
}