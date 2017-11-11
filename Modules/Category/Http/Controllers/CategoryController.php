<?php

namespace Modules\Category\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Category\Dfcore\CategoryHelper;
use Modules\Category\Entities\CategoryTable;
use Modules\Category\Entities\Repository\Contract\iCategoryTable;

class CategoryController extends Controller
{
    private $category_table;
    public function __construct(iCategoryTable $category_table)
    {
        $this->category_table = $category_table;
    }



    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(Request $request)
    {

        $parent_id = (int) $request->get('parent_id');
        $id = (int) $request->get('id');
        $delete = (int) $request->get('delete');
        $Cathelper = new CategoryHelper(new CategoryTable());

        if($delete > 0) {
            $request->session()->flash('flash_info_message', trans('category::messages.category_lbl11'));
            $this->category_table->deleteCategory($delete);
            return redirect()->route('categorytable.create');
        }


        $category = null;
        if($id > 0 ) {
            $category = $this->category_table->getCategories($id);
        }
        $current_id = ($id == 0 ? $parent_id : $id);
        $category_list =$Cathelper->fetchCategoryTreeList(0,'',$current_id);
        return view('category::create')->with(compact('parent_id','id','category','category_list'
        ));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->only(['category','parent']);
        $this->validate($request, [
            'category' => 'required|max:255'
        ]);
        $id = (int) $request->get('id');
        $request->session()->flash('flash_success_message', trans('category::messages.category_lbl2',['category_name'=>$data['category']]));
        if($data['parent'] > 0 ) {
            $this->category_table->createCategory($data);
            return redirect()->route('categorytable.create',['parent_id'=>$data['parent']]);
        } else {
            unset($data['parent']);
            $this->category_table->createCategory($data,$id);
            return redirect()->route('categorytable.create',['id'=>$id]);
        }

    }


}
