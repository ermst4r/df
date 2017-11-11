<?php
namespace App\Entity\Repository\Contract;

interface iXmlMapping {



    public function getXmlMapping($id);
    public function createXmlMapping($data = array());
    public function getPlainMappedFields($feed_id);
    public function isMapped($feed_id);
    public  function removeMapping($id);

}