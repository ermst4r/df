<?php
namespace App\Entity\Repository\Contract;

interface iCustomMapping {



    public function createCustomMapping($data, $id=0);
    public function getCustomMapping($id, $pluck = false,$col ='');
    public function removeCustomMapping($fk_feed_id);


}