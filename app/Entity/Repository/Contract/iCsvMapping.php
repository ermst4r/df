<?php
namespace App\Entity\Repository\Contract;

interface iCsvMapping {



    public function getCsvMapping($id);
    public function createCsvMapping($data = array());
    public function isMapped($feed_id);
    public function getPlainMappedFields($feed_id);
    public  function removeMapping($id);

}