<?php
namespace App\Entity\Repository\Contract;

interface iRuleCondition {


    public function createRuleCondition($data = array(),$id = 0);
    public function getRuleCondition($rule_id);
    public function updateRuleConditionByType($fk_rule_id, $data = []);


}