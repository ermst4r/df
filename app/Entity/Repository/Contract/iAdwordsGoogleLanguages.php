<?php
namespace App\Entity\Repository\Contract;

interface iAdwordsGoogleLanguages {


    public function createLanguages($data, $id = 0);
    public function getLanguages();
    public function removeAllLanguages();



}