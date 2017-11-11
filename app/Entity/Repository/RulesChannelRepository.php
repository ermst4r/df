<?php
namespace App\Entity\Repository;


use App\Entity\Repository\Contract\iRule;
use App\Entity\Repository\Contract\iRuleChannel;
use App\Entity\Rule;
use App\Entity\RulesChannel;

class RulesChannelRepository  extends Repository implements iRuleChannel  {




    /**
     * @param array $data
     */
    public function createRuleChannel($data = array())
    {
        $this->model->create($data);
    }


    /**
     * @param $rule_id
     */
    public function removeRuleChannel($rule_id)
    {
        $this->model->where('fk_rule_id',$rule_id)->delete();
    }



}
?>