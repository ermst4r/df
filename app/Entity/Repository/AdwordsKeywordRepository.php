<?php

namespace App\Entity\Repository;


use DB;
use App\Entity\AdwordsKeyword;
use App\Entity\Repository\Contract\iAdwordsKeyword;


class AdwordsKeywordRepository implements iAdwordsKeyword
{

    private $adwords_keyword;

    /**
     * AdwordsKeywordRepository constructor.
     * @param AdwordsKeyword $adwords_keyword
     */
    public function __construct(AdwordsKeyword $adwords_keyword)
    {
        $this->adwords_keyword = $adwords_keyword;
    }


    /**
     * @param $data
     * @param int $id
     * @return mixed
     */
    public function createKeyword($data, $id = 0)
    {
        if($id == 0 ) {
            return $this->adwords_keyword->create($data);
        } else {
            return $this->adwords_keyword->where('id',$id)->update($data);
        }
    }

    /**
     * @param $fk_adwords_feed_id
     * @param $type
     * @return mixed
     */
    public function getKeyword($fk_adwords_feed_id,$type,$visible=true)

    {
       return $this->adwords_keyword->where('fk_adwords_feed_id',$fk_adwords_feed_id)->where('keyword_option',$type)->where('visible',$visible)->get();

    }


    /**
     * @param $fk_adgroup_preview_id
     * @return mixed
     */
    public function getKeywordsWithNoConnectionToDelete($fk_adwords_feed_id)
    {
        $table = $this->adwords_keyword->getTable();
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
        return $this->adwords_keyword->where('fk_adwords_feed_id',$fk_adwords_feed_id)->where('visible',$visible)->get();


    }



    /**
     * @param $id
     */
    public function removeKeyword($id)
    {
        if($id > 0 )  {
            $this->adwords_keyword->where('id',$id)->delete();
        }

    }


}