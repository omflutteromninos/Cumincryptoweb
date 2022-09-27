<?php
/**
 * Created by PhpStorm.
 * User: wasim
 * Date: 11/28/18
 * Time: 1:26 PM
 */

namespace App\Http\Services;


class BaseService
{

//    public $model = null;
//    public $repository = null;
//    public $object = null;

    public function __construct($model, $repo)
    {

        $this->model = $model;
        $this->repository = $repo;
        $this->object = new $this->repository($this->model);
    }

    public function insert($entity = [])
    {

    }

    public function getAll()
    {

        return $this->object->getAll();

    }

    public function getById($id)
    {
        return $this->object->getById($id);
    }

    public function getDocs($params)
    {
        return $this->object->getDocs($params);
    }


    public function delete($id)
    {
        return $this->object->deleteById($id);
    }

    public function update($where, $update){
        return $this->object->update($where, $update);
    }


}
