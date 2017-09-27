<?php
namespace App\Entity\Repository;


use App\Entity\Repository\Contract\iStore;
use App\Entity\Store;

class StoreRepository  implements iStore  {


    private $store;
    public function __construct(Store $store)
    {
        $this->store =$store;
    }

    /**
     * @param array $data
     */
    public function createStore($data = array(),$id = 0)
    {
        if($id == 0 ) {
            $this->store->create($data);
        } else {
            $this->store->find($id)->update($data);
        }


    }


    /**
     * Get all the stores
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAllStores()
    {
        return $this->store->all();

    }


    /**
     * Get the store
     * @param $id
     * @return mixed
     */
    public function getStore($id)
    {
        return $this->store->findOrFail($id);
    }


}
?>