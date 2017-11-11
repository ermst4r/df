<?php

namespace App\Entity;


use Illuminate\Database\Eloquent\Model;

class Xmlmapping extends Model
{
    protected $table = 'xml_mapping';
    protected $fillable = ['xml_map_name','fk_feed_id','mapped_xml_name'];
}