<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class RuleAdwords extends Model
{
    protected $table = 'rules_adwords';
    protected $fillable = ['fk_adwords_feed_id','fk_rule_id'];

}
