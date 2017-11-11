<?php
namespace Modules\Category\Entities\Repository;

use App\Entity\Repository\Repository;
use Modules\Category\Entities\Repository\Contract\iCategoryTable;

class CategoryTableRepository  extends Repository  implements  iCategoryTable{


    public function createCategory($data, $id = 0)
    {
        if($id == 0) {
            return $this->model->create($data);
        } else {
            return $this->model->where('id',$id)->update($data);
        }
    }

    public function getCategories($id = 0)
    {
        if($id >0 ) {
            return $this->find($id);
        } else {
            return $this->all();
        }

    }

    /**
     * @param int $parent
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getCategoryByParent($parent=0)
    {
        return $this->model->where('parent',$parent)->orderBy('category','asc')->get();
    }


    public function getCategoryArray()
    {
        return $this->all()->pluck('category','id')->toArray();
    }


    public function deleteCategory($id)
    {
        $this->model->find($id)->delete();
        $this->model->where('parent',$id)->delete();
    }


}
?>