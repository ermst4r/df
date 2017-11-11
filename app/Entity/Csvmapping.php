<?php

namespace App\Entity;


use Illuminate\Database\Eloquent\Model;

class Csvmapping extends Model
{
    protected $table = 'csv_mapping';
    protected $fillable = ['csvindex','mapped_field_name','mapped_csv_name','fk_feed_id'];
}