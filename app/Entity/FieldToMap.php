<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class FieldToMap extends Model
{
    protected $table = 'fields_to_map';
    protected $fillable = ['field'];

}
