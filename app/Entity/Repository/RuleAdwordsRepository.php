<?php
namespace App\Entity\Repository;


use App\Entity\Repository\Contract\iRuleAdwords;
use App\Entity\RuleAdwords;
use DB;
class RuleAdwordsRepository  implements iRuleAdwords  {


    private $rule_adwords;

    public function __construct(RuleAdwords $rule_adwords)
    {
        $this->rule_adwords =$rule_adwords;
    }

    /**
     * @param $data
     */
    public function createAdwordsRule($data)
    {

        return $this->rule_adwords->create($data);
    }


    /**
     * @param $adwords_feed_id
     * @return mixed
     */
    public function removeAdwordsRule($rule_id)
    {
        return $this->rule_adwords->where('fk_rule_id',$rule_id)->delete();
    }


}
?>