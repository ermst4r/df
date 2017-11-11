<?php

namespace App\Entity\Repository;


use DB;
use App\Entity\AdwordsKeyword;
use App\Entity\Repository\Contract\iAdwordsKeyword;


class AdwordsKeywordRepository extends Repository  implements iAdwordsKeyword
{

   


    /**
     * @param $data
     * @param int $id
     * @return mixed
     */
    public function createKeyword($data, $id = 0)
    {
        if($id == 0 ) {
            return $this->model->create($data);
        } else {
            return $this->model->where('id',$id)->update($data);
        }
    }

    /**
     * @param $fk_adwords_feed_id
     * @param $type
     * @return mixed
     */
    public function getKeyword($fk_adwords_feed_id,$type,$visible=true)

    {
       return $this->model->where('fk_adwords_feed_id',$fk_adwords_feed_id)->where('keyword_option',$type)->where('visible',$visible)->get();

    }


    /**
     * @param $fk_adgroup_preview_id
     * @return mixed
     */
    public function getKeywordsWithNoConnectionToDelete($fk_adwords_feed_id)
    {
        $table = $this->model->getTable();
        return DB::table($table)
            ->join('ads_keyword_preview', $table.'.id', '=', 'ads_keyword_preview.fk_adwords_keyword_id')
            ->select(DB::RAW($table.'.id AS keyword_id'))
            ->where('ads_keyword_preview.adwords_id',0)
            ->where('adwords_keywords.visible',false)
            ->where($table.'.fk_adwords_feed_id',$fk_adwords_feed_id)
            ->groupBy($table.'.id')
            ->get();
    }



    /**
     * @param $fk_adwords_feed_id
     * @return mixed
     */
    public function getKeywordsFromFeed($fk_adwords_feed_id,$visible=true)
    {
        return $this->model->where('fk_adwords_feed_id',$fk_adwords_feed_id)->where('visible',$visible)->get();


    }



    /**
     * @param $id
     */
    public function removeKeyword($id)
    {
        if($id > 0 )  {
            $this->model->where('id',$id)->delete();
        }

    }


}