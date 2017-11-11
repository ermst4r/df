<?php
namespace App\Entity\Repository\Contract;

interface iAdwordsTarget {


    public function createAdwordsTarget($data, $id=0);
    public function getAdwordsTarget($id);


}