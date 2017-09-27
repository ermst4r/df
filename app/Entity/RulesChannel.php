<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class RulesChannel extends Model
{
    protected $table = 'rules_channel';
    protected $fillable = ['fk_channel_feed_id','fk_rule_id','fk_channel_type_id','visible'];




}
