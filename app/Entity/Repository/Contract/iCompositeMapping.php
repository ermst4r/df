<?php
namespace App\Entity\Repository\Contract;

interface iCompositeMapping {


    public function getCompositeMapping($feed_id);
    public function createCompositeMapping($data=[]);
    public function removeCompositeMapping($feed_id);
    public function hasCompositeMapping($feed_id);


}