<?php

namespace App\Entity;


use Illuminate\Database\Eloquent\Model;

class RuleBol extends Model
{
    protected $table = 'rules_bol';
    protected $fillable = ['fk_bol_id','fk_rule_id'];
}