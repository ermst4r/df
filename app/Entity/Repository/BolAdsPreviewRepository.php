<?php

namespace App\Entity\Repository;

use App\Entity\BoladsPreview;
use App\Entity\CategoryFilter;
use App\Entity\Repository\Contract\iBolAdsPreview;
use DB;
class BolAdsPreviewRepository extends Repository implements iBolAdsPreview
{



    public function updatePreview($data,$id)
    {
        $this->model->where('id',$id)->update($data);
    }

    /**
     * @param $fk_bol_id
     * @return array
     */
    public function pluckPreviewAds($fk_bol_id)
    {
        return $this->model->where('fk_bol_id',$fk_bol_id)->pluck('ean')->toArray();
    }

    /**
     * @param $data
     * @param int $id
     * @return $this|bool|\Illuminate\Database\Eloquent\Model
     */
    public function createAdPreview($data, $id = 0)
    {
        if($id == 0 ) {
            return $this->model->create($data);
        } else {
            return  $this->model->where('id',$id)->update($data);
        }
    }


    /**
     * @param $fk_bol_id
     * @return \Illuminate\Support\Collection
     */
    public function getPreviewAds($fk_bol_id)
    {

        return $this->model->where('fk_bol_id',$fk_bol_id)->get();
    }


    /**
     * @param $data
     * @param $ean
     * @param $fk_bol_id
     * @return bool
     */
    public function updateAdByEan($data, $ean,$fk_bol_id)
    {

        $exists = true;
        if($this->model->where('ean',$ean) ->where('fk_bol_id',$fk_bol_id)->count()  == 1) {
              $this->model->where('ean',$ean)
                ->where('fk_bol_id',$fk_bol_id)
                -> update($data);
        } else {
            $this->createAdPreview($data);
            $exists = false;
        }
        return $exists;



    }


}