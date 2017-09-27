<?php

namespace App\Entity\Repository;


use App\DfCore\DfBs\Enum\RevisionType;
use App\Entity\AdwordsKeyword;
use App\Entity\AdwordsRevision;
use App\Entity\Repository\Contract\iAdwordsRevision;


class AdwordsRevisionRepository implements iAdwordsRevision
{

    private $adwords_revision;

    /**
     * AdwordsKeywordRepository constructor.
     * @param AdwordsKeyword $adwords_keyword
     */
    public function __construct(AdwordsRevision $adwords_revision)
    {
        $this->adwords_revision = $adwords_revision;
    }


    /**
     * @param $fk_adwords_feed_id
     */
    public function getDeleteRevisions($fk_adwords_feed_id)
    {

        return $this->adwords_revision->where('fk_adwords_feed_id',$fk_adwords_feed_id)->where('revision_type',RevisionType::DELETE)->pluck('id','generated_id');
    }

    /**
     * @param $fk_adwords_feed_id
     * @return array
     */
    public function getUpdatedRevisions($fk_adwords_feed_id)
    {
        $returnArray = [];
        foreach($this->adwords_revision->where('fk_adwords_feed_id',$fk_adwords_feed_id)->where('revision_type',RevisionType::UPDATE)->get() as $rev) {
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
            $this->adwords_revision->create($data);
        } else {
            $this->adwords_revision->where('id',$id)->update($data);
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
            $this->adwords_revision->create($data);
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

        if($this->adwords_revision
                ->where('generated_id',$data['generated_id'])
                ->where('revision_field_name',$data['revision_field_name'])
                ->where('fk_ads_preview_id',$data['fk_ads_preview_id'])
                ->where('fk_adwords_feed_id',$data['fk_adwords_feed_id'])
                ->count() > 0) {


            $this->adwords_revision
                ->where('generated_id',$data['generated_id'])
                ->where('revision_field_name',$data['revision_field_name'])
                ->where('fk_adwords_feed_id',$data['fk_adwords_feed_id'])
                ->where('fk_ads_preview_id',$data['fk_ads_preview_id'])
                ->update($data);

        } else {

            $this->adwords_revision->create($data);
        }
    }


}