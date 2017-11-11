<?php
namespace Modules\Category\Entities\Repository\Contract;

interface iCategoryTable {
    public function createCategory($data,$id=0);
    public function getCategories($id=0);
    public function getCategoryByParent($parent=0);
    public function getCategoryArray();
    public function deleteCategory($id);

}