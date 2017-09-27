<?php
namespace App\Entity\Repository;


use App\Entity\Repository\Contract\iRuleCondition;
use App\Entity\RuleCondition;

class RuleConditionRepository  implements iRuleCondition  {


    private $rule_condition;
    public function __construct(RuleCondition $rule_condition)
    {
        $this->rule_condition =$rule_condition;
    }

    public function createRuleCondition($data = array(), $id = 0)
    {
        if($id == 0 ) {
            $id = $this->rule_condition->create($data);
        } else {
            $this->rule_condition->find($id)->update($data);
        }
        return $id;
    }

    public function getRuleCondition($rule_id)
    {
        return $this->rule_condition->where('fk_rule_id',$rule_id)->pluck('rule_options')->toArray();
    }

    public function updateRuleConditionByType($fk_rule_id, $data = [])
    {

        return $this->rule_condition->where('fk_rule_id',$fk_rule_id)

                                    ->update($data);
    }


}
?>