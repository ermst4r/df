<?php

namespace App\Http\Controllers\DfCore;
use App\Entity\Repository\Contract\iStore;
use App\Entity\Store;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class StoreController extends Controller
{
    private $store;

    /**
     * StoreController constructor.
     * @param iStore $store
     */
    public function __construct(iStore $store)
    {
        $this->store = $store;

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id = 0)
    {
        $get_store = null;
        if($id > 0 ) {
            $get_store = $this->store->getStore($id);
        }
        return view('dfcore.store.create',['id'=>$id, 'get_store'=>$get_store]);
    }


    /**
     * @param $id
     */
    public function delete_store(Request $request,$id)
    {
        $store = Store::findOrFail($id);
        $store->delete();
        if($request->session()->get('store_id') == $id) {
            $request->session()->forget('store_id');
        }

        $request->session()->flash('flash_success_message', trans('messages.store_lbl15'));
        return redirect()->route('store.select_store');

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function poststore(Request $request)
    {
       $data = $request->only(['store_name','store_url']);
       $id = (int) $request->get('id');
       $this->store->createStore($data,$id);


       if($id > 0) {
           $request->session()->flash('flash_success_message',trans('messages.store_lbl8', ['store'=>ucfirst($request->get('store_name'))])    );
       } else {
           $request->session()->flash('flash_success_message',trans('messages.store_lbl7', ['store'=>ucfirst($request->get('store_name'))])    );
       }

       return redirect()->route('index.dashboard');
    }


    /**
     *
     * @return mixed
     */
    public function select_store(Request $request)
    {
        $current_store = $request->session()->get('store_id');
        $stores = $this->store->getAllStores();
        return view('dfcore.store.select_store')->with(compact('stores','current_store'));
    }


    /**
     * Set the default store
     */
    public function defaultstore(Request $request,$store_id)
    {
        $request->session()->put('store_id', $store_id);
        return redirect()->route('index.dashboard');
    }


}
