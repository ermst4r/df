<?php
namespace App\Entity\Repository\Contract;

interface iRuleChannel {


    public function createRuleChannel($data = array());
    public function removeRuleChannel($rule_id);



}