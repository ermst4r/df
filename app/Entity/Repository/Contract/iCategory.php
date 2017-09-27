<?php
namespace App\Entity\Repository\Contract;

interface iCategory {


    public function getToCategoryByTerm($term);
    public function createToCategory($data=[]);
    public function getCategories();

}