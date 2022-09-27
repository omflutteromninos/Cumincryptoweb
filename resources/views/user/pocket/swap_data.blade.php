<h3>
    <span class="to-coin-name mr-1">{{check_default_coin_type($to_wallet->coin_type)}}</span>
    <span id="from-per-amount">{{$wallet_rate}}</span>/
    <span class="from-coin-name">{{check_default_coin_type($from_wallet->coin_type)}}</span>
</h3>
<div class="swipe-in-top">
    <div class="swipe-count">
        <input type="text" id="from-amount" value="{{$amount}}" placeholder="0">
        <input type="hidden" id="from-wallet-id" value="{{$from_wallet->id}}">
        <p class="from-coin-name" id="from-coin-name">{{check_default_coin_type($from_wallet->coin_type)}}</p>
    </div>
    <h5>
        <input type="hidden" id="to-wallet-id" value="{{$to_wallet->id}}">
        <span class="loader d-none" id=""><i class="fa fa-spinner fa-spin"></i></span>
        <span class="" id="to-amount">{{$convert_rate}}</span>
        <span class="to-coin-name" id="to-coin-name">{{check_default_coin_type($to_wallet->coin_type)}}</span>
    </h5>
    <span class="swipe-btn"><svg width="14" height="18" viewBox="0 0 14 18" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M10.1418 4.27868L11.2836 5.4205C11.6903 5.82719 12.3491 5.82774 12.7551 5.42173C13.1611 5.01573 13.1606 4.35692 12.7539 3.95023L9.88943 1.08578C9.44225 0.6386 8.71722 0.637996 8.27078 1.08443L5.40375 3.95146C4.99775 4.35746 4.9983 5.01628 5.40498 5.42296C5.81166 5.82964 6.47047 5.83019 6.87648 5.42419L8.01694 4.28372V9.97559C8.01694 10.5279 8.46466 10.9756 9.01694 10.9756H9.14179C9.69407 10.9756 10.1418 10.5279 10.1418 9.97559V4.27868Z" fill="black"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M3.16681 13.7226L2.02498 12.5808C1.6183 12.1741 0.959491 12.1735 0.553487 12.5796C0.147483 12.9856 0.148033 13.6444 0.554714 14.051L3.41917 16.9155C3.86635 17.3627 4.59138 17.3633 5.03781 16.9169L7.90484 14.0498C8.31084 13.6438 8.31029 12.985 7.90361 12.5783C7.49693 12.1716 6.83812 12.1711 6.43211 12.5771L5.29165 13.7176L5.29165 8.02569C5.29165 7.4734 4.84394 7.02569 4.29165 7.02569L4.16681 7.02569C3.61452 7.02569 3.1668 7.47341 3.1668 8.02569L3.16681 13.7226Z" fill="black"></path></svg></span>
</div>
