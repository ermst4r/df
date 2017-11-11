<?php
namespace App\Entity\Repository;


use App\DfCore\DfBs\Enum\RevisionType;
use App\Entity\Repository\Contract\iRevision;
use App\Entity\Revision;

class RevisionRepository  extends Repository implements iRevision  {





    /**
     * Get the updated model data
     * And pluck it to an array...
     * @param $feed_id
     * @return mixed
     */
    public function getUpdatedRevisionData($fk_channel_feed_id)
    {
        return $this->model->where('fk_channel_feed_id',$fk_channel_feed_id)
                ->where('revision_type',RevisionType::UPDATE)
                ->get();

    }


    /**
     * Pluck for us the generated ids in array format
     * @param $feed_id
     * @return mixed
     */
    public function getDeletedRevisionData($fk_channel_feed_id)
    {
        return $this->model->where('fk_channel_feed_id',$fk_channel_feed_id)
                ->where('revision_type',RevisionType::DELETE)
                ->pluck('generated_id')->toArray();
    }

    /**
     * @param array $data
     * @param int $id
     * @return mixed
     */
    public function setUpdateRevision($data = array())
    {
        /**
         * Update where the id, fieldname and feed_id matches
         */
        if($this->model
                ->where('generated_id',$data['generated_id'])
                ->where('revision_field_name',$data['revision_field_name'])
                ->where('fk_feed_id',$data['fk_feed_id'])
                ->where('fk_feed_id',$data['fk_feed_id'])
                ->where('fk_channel_feed_id',$data['fk_channel_feed_id'])
                ->where('fk_channel_type_id',$data['fk_channel_type_id'])
                ->count() > 0) {


                $this->model
                    ->where('generated_id',$data['generated_id'])
                    ->where('revision_field_name',$data['revision_field_name'])
                    ->where('fk_feed_id',$data['fk_feed_id'])
                    ->where('fk_channel_feed_id',$data['fk_channel_feed_id'])
                    ->where('fk_channel_type_id',$data['fk_channel_type_id'])
                    ->update($data);


        } else {

            $this->model->create($data);
        }
        return $data['generated_id'];

    }


    public function setDeleteRevision($ids,$feed_id,$channel_feed_id,$channel_type_id)
    {

        foreach ($ids as $id) {
            $data = [
                'fk_feed_id'=>$feed_id,
                'generated_id'=>$id,
                'revision_type'=>RevisionType::DELETE,
                'fk_channel_feed_id'=>$channel_feed_id,
                'fk_channel_type_id'=>$channel_type_id,
            ];
            $this->model->create($data);
        }

    }

    /**
     * @param $id
     * @return mixed
     *
     */
    public function removeRevision($id)
    {
        return $this->model->where('product_id',$id)->delete();
    }


}
?>