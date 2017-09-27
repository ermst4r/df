<?php


namespace App\Entity\Repository;

use App\Entity\Category;
use App\Entity\Repository\Contract\iCategory;

/**
 * Class XmlMappingRepository
 * @package App\Entity\Repository
 */
class CategoryRepository implements iCategory
{
    private $toCategory;
    public function __construct(Category $toCategory)
    {
        $this->toCategory = $toCategory;
    }

    public function getCategories()
    {
        return $this->toCategory->all();
    }

    /**
     * @return mixed
     */
    public function getToCategoryByTerm($term)
    {
        return  $this->toCategory->where('category_name','like','%'.$term.'%')->get();
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function createToCategory($data = [])
    {
        $to_category = $this->toCategory->create($data);
        return $to_category->id;
    }

}