<?php

namespace App\Entity\Repository;

use App\Entity\BoladsPreview;
use App\Entity\CategoryFilter;
use App\Entity\Repository\Contract\iBolAdsPreview;
use DB;
class BolAdsPreviewRepository implements iBolAdsPreview
{

    private $bol_ads_preview;

    /**
     * CategoryFilterRepository constructor.
     * @param CategoryFilter $categoryFilter
     */
    public function __construct(BoladsPreview $boladsPreview)
    {
        $this->bol_ads_preview = $boladsPreview;
    }

    public function updatePreview($data,$id)
    {
        $this->bol_ads_preview->where('id',$id)->update($data);
    }

    /**
     * @param $fk_bol_id
     * @return array
     */
    public function pluckPreviewAds($fk_bol_id)
    {
        return $this->bol_ads_preview->where('fk_bol_id',$fk_bol_id)->pluck('ean')->toArray();
    }

    /**
     * @param $data
     * @param int $id
     * @return $this|bool|\Illuminate\Database\Eloquent\Model
     */
    public function createAdPreview($data, $id = 0)
    {
        if($id == 0 ) {
            return $this->bol_ads_preview->create($data);
        } else {
            return  $this->bol_ads_preview->where('id',$id)->update($data);
        }
    }


    /**
     * @param $fk_bol_id
     * @return \Illuminate\Support\Collection
     */
    public function getPreviewAds($fk_bol_id)
    {

        return $this->bol_ads_preview->where('fk_bol_id',$fk_bol_id)->get();
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
        if($this->bol_ads_preview->where('ean',$ean) ->where('fk_bol_id',$fk_bol_id)->count()  == 1) {
              $this->bol_ads_preview->where('ean',$ean)
                ->where('fk_bol_id',$fk_bol_id)
                -> update($data);
        } else {
            $this->createAdPreview($data);
            $exists = false;
        }
        return $exists;



    }


}