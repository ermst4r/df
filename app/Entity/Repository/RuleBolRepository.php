<?php

namespace App\Entity\Repository;


use App\Entity\CategoryFilter;
use App\Entity\Repository\Contract\iRuleBol;
use App\Entity\RuleBol;

class RuleBolRepository implements iRuleBol
{

    private $rule_bol;

    /**
     * CategoryFilterRepository constructor.
     * @param CategoryFilter $categoryFilter
     */
    public function __construct(RuleBol $rule_bol)
    {
        $this->rule_bol = $rule_bol;
    }

    /**
     * @param $data
     * @param int $id
     * @return mixed
     */
     public function createBolRule($data, $id = 0)
     {
        if($id == 0) {
           return $this->rule_bol->create($data);
        } else {
            return $this->rule_bol->where('id',$id)->update($data);
        }
     }


}