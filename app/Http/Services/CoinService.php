<?php
/**
 * Created by Masum.
 * User: itech
 * Date: 11/15/18
 * Time: 4:50 PM
 */

namespace App\Http\Services;


use App\Model\Coin;
use App\Repository\AdminCoinRepository;

class CoinService extends BaseService {

    public $model = Coin::class;
    public $repository = AdminCoinRepository::class;

    public function __construct(){
        parent::__construct($this->model,$this->repository);
    }

    public function getCoin($data){
        $object = $this->object->getDocs($data);

        if (empty($object)) {
            return null;
        }

        return $object;
    }


    public function getPrimaryCoin()
    {
        $coinRepo = new CoinRepository($this->model);
        $object = $this->object->getPrimaryCoin();

        return $object;
    }

    public function getBuyableCoin()
    {
        $object = $this->object->getBuyableCoin();
        if (empty($object)) {
            return null;
        }

        return json_encode($object);
    }

    public function getBuyableCoinDetails($coinId){
        $object = $this->object->getBuyableCoinDetails($coinId);
        if (empty($object)) {
            return null;
        }
        return json_encode($object);
    }

    public function generate_address($coinId)
    {
        $address='';

        $coinApiCredential = $this->object->getCoinApiCredential($coinId);
        if(isset($coinApiCredential)){
            //TODO Need to fix it
            $api = new BitCoinApiService($coinApiCredential->user, $coinApiCredential->password, $coinApiCredential->host, $coinApiCredential->port);
            $address = $api->getNewAddress();
        }

        return json_encode($address);
    }

    public function getCoinApiCredential($coinId){
        $coinRepo = new CoinRepository($this->model);
        $object = $coinRepo->getCoinApiCredential($coinId);
        if (empty($object)) {
            return null;
        }
        return $object;
    }

    public function addCoin($data,$coin_id=null){
        try{

            if(!empty($coin_id)){
                $updateableCoin = Coin::find($coin_id);
                if($updateableCoin->type == DEFAULT_COIN_TYPE) {
                    $data['status'] = STATUS_ACTIVE;
                }
                $coin = $this->object->updateCoin($coin_id,$data);
            }else{
//                if (empty($data['coin_icon'])) {
//                    return ['success' => false, 'message' => 'Coin icon can not be empty.'];
//                }
                $coin = $this->object->addCoin($data);
            }

            return ['success'=>true,'data'=>$coin,'message'=>__('updated successful.')];
        } catch(\Exception $e) {
            return ['success'=>false,'data'=>null,'message'=>'something.went.wrong'];
        }
    }

    public function getCoinDetailsById($coinId){
        try{
            $coin = $this->object->getCoinDetailsById($coinId);
            return ['success'=>true,'data'=>$coin,'message'=>'successfull.'];
        }catch(\Exception $e){
            return ['success'=>false,'data'=>null,'message'=>'something.went.wrong'];
        }
    }
}
