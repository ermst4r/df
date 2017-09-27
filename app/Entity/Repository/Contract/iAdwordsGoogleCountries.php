<?php
namespace App\Entity\Repository\Contract;

interface iAdwordsGoogleCountries {


    public function createCountries($data,$id=0);
    public function getCountries();



}