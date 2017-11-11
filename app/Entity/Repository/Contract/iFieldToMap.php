<?php
namespace App\Entity\Repository\Contract;

interface iFieldToMap {



    public function getField($id = 0);
    public function createField($data = array(),$id = 0);



}