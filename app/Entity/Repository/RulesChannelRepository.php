<?php
namespace App\Entity\Repository;


use App\Entity\Repository\Contract\iRule;
use App\Entity\Repository\Contract\iRuleChannel;
use App\Entity\Rule;
use App\Entity\RulesChannel;

class RulesChannelRepository  implements iRuleChannel  {


    private $rule_channel;
    public function __construct(RulesChannel $rule_channel)
    {
        $this->rule_channel =$rule_channel;
    }

    /**
     * @param array $data
     */
    public function createRuleChannel($data = array())
    {
        $this->rule_channel->create($data);
    }


    /**
     * @param $rule_id
     */
    public function removeRuleChannel($rule_id)
    {
        $this->rule_channel->where('fk_rule_id',$rule_id)->delete();
    }



}
?>