<?php

namespace App\Repository;


use App\Model\Coin;

class AdminCoinRepository extends CommonRepository
{

    function __construct($model)
    {
        parent::__construct($model);
    }

    public function update($where, $update){
        return $this->model::where($where)->update($update);
    }

    /**
     * @return Coin->primary active Coin
     */
    public function getPrimaryCoin()
    {
        return Coin::where(['active_status' => 1, 'is_primary' => 1])->first();
    }

    /**
     * @return Coin that are buy able
     */
    public function getBuyableCoin()
    {
        $query = Coin::select('coins.*')->where(['is_buyable' => 1, 'active_status' => 1])->get();
        return $query;
    }

    /**
     * @param $coinId
     * @return Coin details for the by able Coins
     */
    public function getBuyableCoinDetails($coinId)
    {
        $query = Coin::select('coins.*')->where(['is_buyable' => 1, 'active_status' => 1, 'id' => $coinId])->first();
        return $query;
    }

    /**
     * @param $coinId
     * @return Coin Api credentials
     */
    public function getCoinApiCredential($coinId){
        $query = Coin::join('coin_settings','coin_settings.coin_id','=','coins.id')
            ->where(['coins.id'=>$coinId])
            ->first();
        return $query;
    }

    /**
     * @param $data
     * @return Coin that is created
     */
    public function addCoin($data){
        return Coin::create($data);
    }

    /**
     * @param $coin_id
     * @param $data
     * @return Coin that is updated
     */
    public function updateCoin($coin_id, $data){
//        $service = new UserWalletService();
//        $service->createAllUserWallet($coin_id);
        return Coin::where(['id'=>$coin_id])->update($data);
    }

    /**
     * @param $coinId
     * @return Coin details by $coinId
     */
    public function getCoinDetailsById($coinId){
        $query = Coin::select('coins.*')->where(['id' => $coinId])->first();
        return $query;
    }

    /**
     * @return Coin that are currency
     */
    public function getCurrencyList()
    {
        $query = Coin::select('coin_type', 'full_name')->where(['is_currency' => 1])->get();

        return $query;
    }
}
