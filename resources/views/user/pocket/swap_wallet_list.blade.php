@if(isset($wallets[0]))
    <div class="mn-tp">
        <span>{{__('Swap with')}}</span>
    </div>
    <button type="button" class="swip-button">
        <span  class="swip-img"><p>{{__('Select')}}</p></span>
    </button>
    <ul class="sh">
        @foreach($wallets as $wallet)
            <li data-to_wallet_id="{{$wallet->wallet_id}}" class="swip-item" data-from_coin_type="{{$wallet->type}}">
                <span class="swip-img">
                <p>{{$wallet->wallet_name .'('.check_default_coin_type($wallet->type) .')'}}</p>
                </span>
            </li>
        @endforeach
    </ul>
@else
    <div class="mn-tp">
        <span class="text-warning">{{__('Please add some wallet with BTC,ETH,DOGE etc coin type first then try to convert')}}</span>
    </div>
@endif
