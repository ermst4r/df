<?php
namespace App\Entity\Repository\Contract;

interface iStore {


    public function createStore($data = array(),$id = 0);
    public function getAllStores();
    public function getStore($id);

}