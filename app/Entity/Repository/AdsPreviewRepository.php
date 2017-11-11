<?php

namespace App\Entity\Repository;


use App\Entity\AdsPreview;
use App\Entity\AdwordsAd;
use App\Entity\Repository\Contract\iAdsPreview;
use DB;

class AdsPreviewRepository extends Repository implements iAdsPreview
{

  
    /**
     * @param $fk_adwords_feed_id
     * @return mixed
     */
    public function countPreviewAds($fk_adwords_feed_id)
    {
        return $this->model
            ->where('fk_adwords_feed_id',$fk_adwords_feed_id)
            ->count();
    }

    /**
     * @param $fk_adwords_feed_id
     * @return mixed
     */
    public function getAdsToDelete($fk_adwords_feed_id)
    {

        $table = $this->model->getTable();
        return DB::table($table)
            ->join('adgroup_preview', $table.'.fk_adgroup_preview_id', '=', 'adgroup_preview.id')
            ->select(DB::RAW($table.'.adwords_id AS ads_adwords_id,adgroup_preview.adwords_id AS adgroup_adwords_id, '.$table.'.id  AS id'))
            ->where($table.'.fk_adwords_feed_id',$fk_adwords_feed_id)
            ->where($table.'.delete_from_adwords',true)
            ->get();

    }

    /**
     * @param $id
     * @return mixed
     */
    public function removeSingleAd($id)
    {
        return $this->model->where('id',$id)->delete();
    }


    /**
     * Get valid ads
     * @param $fk_campaigns_preview_id
     * @param $fk_adgroup_preview_id
     * @return mixed
     */
    public function getValidActiveAdwordsAds($fk_campaigns_preview_id, $fk_adgroup_preview_id, $is_valid=true)
    {
        return $this->model
            ->where('fk_campaigns_preview_id',$fk_campaigns_preview_id)
            ->where('fk_adgroup_preview_id',$fk_adgroup_preview_id)
            ->where('is_valid',$is_valid)
            ->where('adwords_id',0)
            ->get();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getAd($id)
    {
        return $this->model->findOrFail($id);
    }


    /**
     * @param $fk_adwords_feed_id
     * @return mixed
     */
    public function getHotPreviewAds($fk_adwords_feed_id,$fk_campaigns_preview_id,$fk_adgroup_preview_id)
    {

        if($fk_campaigns_preview_id > 0 && $fk_adgroup_preview_id > 0) {
            $ads =  $this->model->where('fk_adwords_feed_id',$fk_adwords_feed_id)->where('fk_campaigns_preview_id',$fk_campaigns_preview_id)
            ->where('fk_adgroup_preview_id',$fk_adgroup_preview_id)->get();
        } else {
            $ads =  $this->model->where('fk_adwords_feed_id',$fk_adwords_feed_id)->get();
        }
        
        $data['data'] = [];
        foreach($ads as $ad) {
            $data['data'][] =
                [
                    $ad->id,
                    $ad->headline_1,
                    $ad->headline_2,
                    $ad->description,
                    $ad->path_1,
                    $ad->path_2,
                    $ad->final_url,
                ];
        }
        $data['field_names'] = ['id','headline_1','headline_2','description','path_1','path_2','final_url'];
        return $data;
    }

    /**
     * @param $id
     */
    public function removeAdByProductId($id,$fk_adwords_ad_id)
    {
        $this->model
            ->where('generated_id',$id)
            ->where('fk_adwords_ad_id',$fk_adwords_ad_id)->
            delete();
    }


    /**
     * @param $data
     * @param $id
     * @param $fk_adwords_ad_id
     * @return mixed
     */
    public function updateAdByMultipleId($data,$id,$fk_adwords_ad_id)
    {
       return  $this->model->where('id',$id)->where('fk_adwords_ad_id',$fk_adwords_ad_id)->update($data);
    }


    /**
     * @param $data
     */
    public function createAdPreview($data,$id=0)
    {

        if($id == 0 ) {
            return $this->model->create($data);
        } else {
            $this->model->where('id',$id)->update($data);
        }

    }

    /**
     * Get the ads.
     * @param $fk_campaigns_preview_id
     * @param $fk_adgroup_preview_id
     * @return mixed
     */
    public function getAdsFromCampaignAndAdgroup($fk_campaigns_preview_id, $fk_adgroup_preview_id)
    {
        return $this->model   ->where('fk_campaigns_preview_id',$fk_campaigns_preview_id)
                                    ->where('fk_adgroup_preview_id',$fk_adgroup_preview_id)
                                    ->get();
    }


    /**
     * @param $fk_campaigns_preview_id
     * @param $fk_adgroup_preview_id
     * @return mixed
     */
    public function getAdsApiErrors($fk_campaigns_preview_id, $fk_adgroup_preview_id)
    {
        return $this->model   ->where('fk_campaigns_preview_id',$fk_campaigns_preview_id)
            ->where('fk_adgroup_preview_id',$fk_adgroup_preview_id)
            ->where('adwords_api_message','!=','')
            ->get();
    }


    /**
     * @param $fk_campaigns_preview_id
     * @param $fk_adgroup_preview_id
     * @param $fk_adwords_ad_id
     * @return mixed
     */
    public function getPreviewAdWordsOptions($fk_campaigns_preview_id,$fk_adgroup_preview_id,$fk_adwords_ad_id)
    {
        $results =  $this->model
            ->where('fk_campaigns_preview_id',$fk_campaigns_preview_id)
            ->where('fk_adgroup_preview_id',$fk_adgroup_preview_id)
            ->where('fk_adwords_ad_id',$fk_adwords_ad_id)
            ->get();
        $return_array = [];
        foreach($results as $res) {

            $return_array[$res->generated_id] =
                [
                    'update_hash'=>$res->update_hash,
                    'adwords_id'=>$res->adwords_id,
                    'id'=>$res->id,
                    'generated_id'=>$res->generated_id
                ];
        }

        return $return_array;
    }

    /**
     * @param $fk_adwords_feed_id
     * @return array
     */
    public function getPreviewAdsArrayMerged($fk_campaigns_preview_id,$fk_adgroup_preview_id,$fk_adwords_ad_id)
    {


       return $this->model
                    ->where('fk_campaigns_preview_id',$fk_campaigns_preview_id)
                    ->where('fk_adgroup_preview_id',$fk_adgroup_preview_id)
                    ->where('fk_adwords_ad_id',$fk_adwords_ad_id)
                    ->pluck('id','generated_id')->toArray();





    }


    /**
     * @param $id
     */
    public function removeAdPreview($id)
    {
        $this->model->where('id',$id)->delete();
    }

    /**
     * @param $fk_campaigns_preview_id
     * @return mixed
     */
    public function removeInvalidAds($fk_campaigns_preview_id)
    {
        return $this->model->where('fk_campaigns_preview_id',$fk_campaigns_preview_id)->where('is_valid',false)->delete();
    }

}