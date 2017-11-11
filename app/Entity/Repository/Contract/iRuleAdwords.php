<?php
namespace App\Entity\Repository\Contract;

interface iRuleAdwords {

    public function createAdwordsRule($data);
    public function removeAdwordsRule($rule_id);



}