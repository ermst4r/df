<?php
namespace App\Entity\Repository;


use App\Entity\Repository\Contract\iRule;
use App\Entity\Rule;
use DB;
class RuleRepository  extends Repository implements iRule  {




    /**
     * @param int $adwords_feed_id
     * @param string $order
     * @return mixed
     */
    public function getAdwordsOrderRules($adwords_feed_id=0,$order='asc')
    {
        $table = $this->model->getTable();
            return DB::table($table)
                ->join('rules_adwords', $table.'.id', '=', 'rules_adwords.fk_rule_id')
                ->select(DB::RAW($table.'.id AS rule_id,'.$table.".*, rules_adwords.*"))
                ->where('rules_adwords.fk_adwords_feed_id',$adwords_feed_id)
                ->where($table.'.visible',true)
                ->orderBy($table.'.order',$order)
                ->get();

    }


    /**
     * @param $bol_id
     * @param string $order
     */
    public function getBolOrderdRules($bol_id, $order = 'asc')
    {
        $table = $this->model->getTable();
        return DB::table($table)
            ->join('rules_bol', $table.'.id', '=', 'rules_bol.fk_rule_id')
            ->select(DB::RAW($table.'.id AS rule_id,'.$table.".*, rules_bol.*"))
            ->where('rules_bol.fk_bol_id',$bol_id)
            ->where($table.'.visible',true)
            ->orderBy($table.'.order',$order)
            ->get();
    }

    /**
     * @param $fk_channel_feed_id
     * @param int $fk_channel_type_id
     * @param string $order
     * @return mixed
     */
    public function getChannelOrdersRules($fk_channel_feed_id,$fk_channel_type_id=0,$order='asc')
    {
        $table = $this->model->getTable();
        if($fk_channel_type_id == 0 ) {
            return DB::table($table)
                ->join('rules_channel', $table.'.id', '=', 'rules_channel.fk_rule_id')
                ->select(DB::RAW($table.'.id AS rule_id,'.$table.".*, rules_channel.*"))
                ->where('rules_channel.fk_channel_feed_id',$fk_channel_feed_id)
                ->where($table.'.visible',true)->orderBy($table.'.order',$order)->get();
        } else {
            return DB::table($table)
                ->join('rules_channel', $table.'.id', '=', 'rules_channel.fk_rule_id')
                ->select(DB::RAW($table.'.id AS rule_id,'.$table.".*, rules_channel.*"))
                ->where('rules_channel.fk_channel_feed_id',$fk_channel_feed_id)
                ->where('rules_channel.fk_channel_type_id',$fk_channel_type_id)
                ->where($table.'.visible',true)->orderBy($table.'.order',$order)->get();
        }

    }


    /**
     * @param array $data
     * @param int $id
     * @return int
     */
    public function createRule($data = array(), $id = 0)
    {
       if($id == 0 ) {
           $id = $this->model->create($data);
       } else {
           $this->model->find($id)->update($data);
       }
       return $id;
    }

    /**
     * @param $fk_feed_id
     */
    public function getRule($id=0,$multi=false)
    {
        if($id > 0 ) {
            if($multi ) {
                return $this->model->where('id',$id)->get();
            }
            return $this->model->findOrFail($id);
        } else {
            return $this->model->all();
        }

    }


    /**
     * @param $id
     */
    public function removeRule($id)
    {
        $this->model->findOrFail($id)->delete();
    }

    /**
     * @param $fk_feed_id
     * @return mixed
     */
    public function getOrderdRules($id,$order='asc',$is_channel_feed = false)
    {
        if(!$is_channel_feed)  {
            return $this->model->where('fk_feed_id',$id)->where('visible',true)->orderBy('order',$order)->get();
        } else {
            return $this->model->where('fk_channel_feed_id',$id)->where('visible',true)->orderBy('order',$order)->get();
        }

    }


    /**
     * @param $fk_channel_feed_id
     * @return mixed
     */
    public function getRuleIdsFromChannel($fk_channel_feed_id)
    {
        $table = $this->model->getTable();
        return DB::table($table)
            ->join('rules_channel', $table.'.id', '=', 'rules_channel.fk_rule_id')
            ->where('rules_channel.fk_channel_feed_id',$fk_channel_feed_id)
            ->where($table.'.visible',true)
            ->pluck($table.'.id')->toArray();

    }



    /**
     * @param $fk_channel_feed_id
     * @return mixed
     */
    public function getRuleIdFromAdwords($adwords_feed_id)
    {
        $table = $this->model->getTable();
        return DB::table($table)
            ->join('rules_adwords', $table.'.id', '=', 'rules_adwords.fk_rule_id')
            ->where('rules_adwords.fk_adwords_feed_id',$adwords_feed_id)
            ->where($table.'.visible',true)
            ->pluck($table.'.id')->toArray();

    }


    /**
     * @param $fk_channel_feed_id
     * @return mixed
     */
    public function getRuleIdsFromBol($bol_id)
    {
        $table = $this->model->getTable();
        return DB::table($table)
            ->join('rules_bol', $table.'.id', '=', 'rules_bol.fk_rule_id')
            ->where('rules_bol.fk_bol_id',$bol_id)
            ->where($table.'.visible',true)
            ->pluck($table.'.id')->toArray();

    }


}
?>