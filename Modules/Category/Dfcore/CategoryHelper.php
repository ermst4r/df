<?php

namespace Modules\Category\Dfcore;

use Modules\Category\Entities\Repository\CategoryTableRepository;

class CategoryHelper extends  CategoryTableRepository
{


    public function __construct($model)
    {
        parent::__construct($model);
    }

    /**
     * Recursive function
     * @param int $parent
     * @param string $user_tree_array
     * @return array|string
     */
    public function fetchCategoryTreeList($parent = 0, $user_tree_array = '',$current=0) {

        if (!is_array($user_tree_array)) {
            $user_tree_array = array();
        }

        $results = $this->getCategoryByParent($parent);

        if (count($results) > 0) {
            $user_tree_array[] = "<ul>";

            foreach($results as $res) {
                $class = '';
                if($res->id == $current) {

                    $class = ' style="text-decoration:underline; color:black; font-weigth:bold;"';
                }
                $user_tree_array[] = " <li><a href=".route('categorytable.create',['id'=>$res->id]).$class." >". $res->category."</a> 
                <a href='".route('categorytable.create',['parent_id'=>$res->id])."' title='".trans('category::messages.category_lbl10')."' style='color: green; margin-left:10px;'> <i class='fa fa-plus'></i> </a>
               
                 <a style='color: red;' href=\"javascript:deleteconfirm('".trans('category::messages.category_lbl12')."','".trans('category::messages.category_lbl14')."',
                 '".route('categorytable.create',['delete'=>$res->id])."')\"><i class='fa fa-trash'></i></a>
                 </li>";
                $user_tree_array = $this->fetchCategoryTreeList($res->id, $user_tree_array,$current);
            }
            $user_tree_array[] = "</ul>";
        }
        return $user_tree_array;
    }
}
