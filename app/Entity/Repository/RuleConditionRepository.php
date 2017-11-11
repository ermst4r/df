<?php
namespace App\Entity\Repository;


use App\Entity\Repository\Contract\iRuleCondition;
use App\Entity\RuleCondition;

class RuleConditionRepository  extends Repository implements iRuleCondition  {




    public function createRuleCondition($data = array(), $id = 0)
    {
        if($id == 0 ) {
            $id = $this->model->create($data);
        } else {
            $this->model->find($id)->update($data);
        }
        return $id;
    }

    public function getRuleCondition($rule_id)
    {
        return $this->model->where('fk_rule_id',$rule_id)->pluck('rule_options')->toArray();
    }

    public function updateRuleConditionByType($fk_rule_id, $data = [])
    {

        return $this->model->where('fk_rule_id',$fk_rule_id)

                                    ->update($data);
    }


}
?>