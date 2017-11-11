<?php
namespace App\Entity\Repository;


use App\Entity\Repository\Contract\iStore;
use App\Entity\Store;

class StoreRepository extends Repository  implements iStore  {


   

    /**
     * @param array $data
     */
    public function createStore($data = array(),$id = 0)
    {
        if($id == 0 ) {
            $this->model->create($data);
        } else {
            $this->model->find($id)->update($data);
        }


    }


    /**
     * Get all the stores
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAllStores()
    {
        return $this->model->all();

    }


    /**
     * Get the model
     * @param $id
     * @return mixed
     */
    public function getStore($id)
    {
        return $this->model->findOrFail($id);
    }


}
?>