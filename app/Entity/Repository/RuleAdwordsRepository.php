<?php
namespace App\Entity\Repository;


use App\Entity\Repository\Contract\iRuleAdwords;
use App\Entity\RuleAdwords;
use DB;
class RuleAdwordsRepository  extends Repository implements iRuleAdwords  {




    /**
     * @param $data
     */
    public function createAdwordsRule($data)
    {

        return $this->model->create($data);
    }


    /**
     * @param $adwords_feed_id
     * @return mixed
     */
    public function removeAdwordsRule($rule_id)
    {
        return $this->model->where('fk_rule_id',$rule_id)->delete();
    }


}
?>