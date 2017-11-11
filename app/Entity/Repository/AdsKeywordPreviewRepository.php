<?php

namespace App\Entity\Repository;



use App\DfCore\DfBs\Enum\AdwordsOptions;
use App\Entity\AdsKeywordPreview;
use App\Entity\AdwordsAd;
use DB;
use App\Entity\Repository\Contract\iAdsKeywordPreview;


class AdsKeywordPreviewRepository extends Repository implements iAdsKeywordPreview
{

 

    /**
     * @param $data
     * @param int $id
     * @return mixed
     */
    public function createPreview($data, $id = 0)
    {
        if($id == 0 ) {
          return  $this->model->create($data);
        } else {
            return $this->model->where('id',$id)->update($data);
        }

    }

    /**
     * @param $fk_adgroup_preview_id
     * @param $formatted_keyword
     * @return mixed
     */
    public function keywordExistsInPreview($fk_adgroup_preview_id, $formatted_keyword,$keyword_type = AdwordsOptions::NORMAL_KEYWORD)
    {
        return $this->model
            ->where('fk_adgroup_preview_id',$fk_adgroup_preview_id)
            ->where('formatted_keyword',$formatted_keyword)
            ->where('keyword_type',$keyword_type)
            ->first();
    }



    public function setKeywordDeletedFromKeyword($keyword_id)
    {
        return $this->model
            ->where('fk_adwords_keyword_id',$keyword_id)
            ->update(['delete_keyword'=>true]);
    }

    /**
     * @param $fk_adgroup_preview_id
     * @return mixed
     */
    public function setKeywordAsDeleteFromUpdate($fk_adgroup_preview_id)
    {
        return $this->model
            ->where('fk_adgroup_preview_id',$fk_adgroup_preview_id)
            ->update(['delete_keyword'=>true]);
    }


    /**
     * @param $fk_adgroup_preview_id
     * @param int $adwords_id
     * @return mixed
     */
    public function getDeletedKeywords($fk_adwords_feed_id,$adwords_id=0)
    {

        if($adwords_id >0 ) {
            return $this->model
                ->where('fk_adwords_feed_id',$fk_adwords_feed_id)
                ->where('adwords_id','>',0)

                ->where('delete_keyword','=',true)
                ->get();
        } else {
           return $this->model
               ->where('fk_adwords_feed_id',$fk_adwords_feed_id)
               ->where('adwords_id',0)
               ->where('delete_keyword','=',true)
               ->get();

        }

    }

    /**
     * @param $id
     * @return mixed
     */
    public function getKeyWordsFromMainKeyword($id)
    {
        return $this->model->where('fk_adwords_keyword_id',$id)->get();
    }

    /**
     * @param $id
     */
    public function removePreviewKeyword($id)
    {
        return $this->model->where('id',$id)->delete();
    }

    /**
     * @param $fk_adgroup_preview_id
     * @return mixed
     */
    public function getKeywordWithDetails($fk_adgroup_preview_id)
    {
        $table = $this->model->getTable();
        return DB::table($table)
            ->join('adwords_keywords', $table.'.fk_adwords_keyword_id', '=', 'adwords_keywords.id')
            ->select(DB::RAW($table.'.formatted_keyword AS keyword, '.$table.'.id AS keyword_preview_id, '.$table.'.keyword_type AS keyword_type,adwords_keywords.keyword_option AS keyword_option'))
            ->where($table.'.fk_adgroup_preview_id',$fk_adgroup_preview_id)
            ->where('adwords_keywords.visible',true)
            ->get();
    }


    /**
     * @param $fk_adwords_feed_id
     * @return mixed
     */
    public function getKeywordsToDeleteFromAdwords($fk_adwords_feed_id)
    {
        $table = $this->model->getTable();
        return DB::table($table)
            ->join('adgroup_preview', $table.'.fk_adgroup_preview_id', '=', 'adgroup_preview.id')
            ->select(DB::RAW($table.'.adwords_id AS keyword_adwords_id, adgroup_preview.adwords_id AS adgroup_adwords_id, ads_keyword_preview.id AS keyword_id, ads_keyword_preview.fk_adwords_keyword_id AS fk_adwords_keyword_id '))
            ->where($table.'.fk_adwords_feed_id',$fk_adwords_feed_id)
            ->where($table.'.adwords_id','>',0)
            ->where($table.'.delete_keyword',true)

            ->get();
    }
}