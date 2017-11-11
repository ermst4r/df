<?php
namespace App\Entity\Repository\Contract;

interface iBolAdsPreview {


    public function createAdPreview($data, $id=0);
    public function getPreviewAds($fk_bol_id);
    public function updateAdByEan($data, $ean,$fk_bol_id);
    public function pluckPreviewAds($fk_bol_id);
    public function updatePreview($data,$id);




}