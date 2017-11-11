<?php

namespace App\Entity\Repository;


use App\Entity\AdCampaignPreview;
use App\Entity\AdwordsAd;
use App\Entity\Repository\Contract\iAdCampaignPreview;
use DB;


class AdCampaignPreviewRepository  extends Repository implements iAdCampaignPreview
{




    public function removeExistingCampaignFromPreview($fk_adwords_feed_id,$existing_campaign)
    {
        if($existing_campaign == 0 ) {
            return $this->model->where('fk_adwords_feed_id',$fk_adwords_feed_id)->delete();
        } else {
            return $this->model->where('fk_adwords_feed_id',$fk_adwords_feed_id)->where('existing_campaign',$existing_campaign)->delete();
        }


    }


    /**
     * @param $id
     */
    public function removeSingleCampaign($id)
    {
        return $this->model->where('id',$id)->delete();
    }

    /**
     * @param $fk_adwords_feed_id
     * @param $campaign_name
     * @return mixed
     */
    public function campaignExists($fk_adwords_feed_id,$campaign_name)
    {
        return $this->model->where('fk_adwords_feed_id',$fk_adwords_feed_id)
                                        ->where('campaign_name',$campaign_name)
                                        ->first() ;
    }




    /**
     * @param $fk_adwords_feed_id
     * @return mixed
     */
    public function getPreviewCampaigns($fk_adwords_feed_id, $to_array = true)
    {
        if($to_array) {
            return $this->model->where('fk_adwords_feed_id',$fk_adwords_feed_id)->pluck('id','campaign_name')->toArray();
        } else {
            return $this->model->where('fk_adwords_feed_id',$fk_adwords_feed_id)->get();
        }

    }

    /**
     * @param $data
     * @return mixed
     */
    public function createCampaignPreview($data,$id=0)
    {
        if($id > 0) {
           return $this->model->where('id',$id)->update($data);
        } else {
            return $this->model->create($data);
        }

    }


    /**
     * @param $id
     * @return mixed
     */
    public function removeAdCampaignPreview($id)
    {
        return $this->model->where('fk_adwords_feed_id',$id)->delete();
    }


    /**
     * @param $fk_adwords_feed_id
     * @return mixed
     */
    public function getAdgroups($fk_adwords_feed_id)
    {
        $table = $this->model->getTable();
        return DB::table($table)
            ->join('adgroup_preview', $table.'.id', '=', 'adgroup_preview.fk_campaigns_preview_id')
            ->where($table.'.fk_adwords_feed_id','=',$fk_adwords_feed_id)
            ->select(
                DB::RAW($table.'.campaign_name AS campaign_name,
                 adgroup_preview.adgroup_name AS adgroup_name,
                 
                 (SELECT COUNT(*) FROM ads_preview WHERE 
                    ads_preview.fk_adgroup_preview_id = adgroup_preview.id AND ads_preview.fk_campaigns_preview_id = '.$table.'.id) AS no_of_ads,
                      (SELECT COUNT(*) FROM ads_preview WHERE 
                    ads_preview.fk_adgroup_preview_id = adgroup_preview.id AND ads_preview.fk_campaigns_preview_id = '.$table.'.id AND ads_preview.errors !="") AS count_errors,
                 '.$table.'.id AS campaign_preview_id, 
                 adgroup_preview.id AS adgroup_preview_id')
            )
            ->get();

    }

    /**
     * @param $fk_adwords_feed_id
     */
    public function getCampaignsToDelete($fk_adwords_feed_id)
    {

       return $this->model->where('fk_adwords_feed_id',$fk_adwords_feed_id)->where('delete_from_adwords',true)->get();
    }


}