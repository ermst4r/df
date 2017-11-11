<?php


namespace App\Entity\Repository;

use App\Entity\Category;
use App\Entity\Repository\Contract\iCategory;

/**
 * Class XmlMappingRepository
 * @package App\Entity\Repository
 */
class CategoryRepository extends Repository implements iCategory
{


    public function getCategories()
    {
        return $this->model->all();
    }

    /**
     * @return mixed
     */
    public function getToCategoryByTerm($term)
    {
        return  $this->model->where('category_name','like','%'.$term.'%')->get();
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function createToCategory($data = [])
    {
        $to_category = $this->model->create($data);
        return $to_category->id;
    }

}