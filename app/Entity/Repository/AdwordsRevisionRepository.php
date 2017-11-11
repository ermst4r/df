<?php

namespace App\Entity\Repository;


use App\DfCore\DfBs\Enum\RevisionType;
use App\Entity\AdwordsKeyword;
use App\Entity\AdwordsRevision;
use App\Entity\Repository\Contract\iAdwordsRevision;


class AdwordsRevisionRepository extends Repository implements iAdwordsRevision
{

 

    /**
     * @param $fk_adwords_feed_id
     */
    public function getDeleteRevisions($fk_adwords_feed_id)
    {

        return $this->model->where('fk_adwords_feed_id',$fk_adwords_feed_id)->where('revision_type',RevisionType::DELETE)->pluck('id','generated_id');
    }

    /**
     * @param $fk_adwords_feed_id
     * @return array
     */
    public function getUpdatedRevisions($fk_adwords_feed_id)
    {
        $returnArray = [];
        foreach($this->model->where('fk_adwords_feed_id',$fk_adwords_feed_id)->where('revision_type',RevisionType::UPDATE)->get() as $rev) {
            $returnArray[$rev->generated_id][] = [
                'revision_field_name'=>$rev->revision_field_name,
                'revision_new_content'=>$rev->revision_new_content,
            ];
        }
        return $returnArray;
    }


    /**
     * @param $data
     * @param $id
     */
    public function createRevision($data, $id)
    {
        if($id > 0 ) {
            $this->model->create($data);
        } else {
            $this->model->where('id',$id)->update($data);
        }
    }


    /**
     * @param $ids
     * @param $fk_ads_preview_id
     * @param $fk_adwords_feed_id
     */

    public function setDeleteRevision($ids,$fk_ads_preview_id,$fk_adwords_feed_id)
    {

        foreach ($ids as $id) {
            $data = [
                'generated_id'=>$id,
                'revision_type'=>RevisionType::DELETE,
                'fk_ads_preview_id'=>$fk_ads_preview_id,
                'fk_adwords_feed_id'=>$fk_adwords_feed_id,
                'revision_field_name'=>null,
                'revision_new_content'=>null,
            ];
            $this->model->create($data);
        }

    }


    /**
     * @param $data
     * @param $generated_id
     */
    public function addRevision($data)
    {
        /**
         * Update where the id, fieldname and feed_id matches
         */

        if($this->model
                ->where('generated_id',$data['generated_id'])
                ->where('revision_field_name',$data['revision_field_name'])
                ->where('fk_ads_preview_id',$data['fk_ads_preview_id'])
                ->where('fk_adwords_feed_id',$data['fk_adwords_feed_id'])
                ->count() > 0) {


            $this->model
                ->where('generated_id',$data['generated_id'])
                ->where('revision_field_name',$data['revision_field_name'])
                ->where('fk_adwords_feed_id',$data['fk_adwords_feed_id'])
                ->where('fk_ads_preview_id',$data['fk_ads_preview_id'])
                ->update($data);

        } else {

            $this->model->create($data);
        }
    }


}