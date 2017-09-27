<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class RuleCondition extends Model
{
    protected $table = 'rules_condition';
    protected $fillable = ['fk_rule_id','rule_options'];

}
