<div class="row mt-4">
    <div class="col-lg-4 offset-lg-1">
        <div class="qr-img text-center">
            @if(!empty($wallet_address) && !empty($wallet_address->address))  {!! QrCode::size(300)->generate($wallet_address->address); !!}
            @else
                {!! QrCode::size(300)->generate('0'); !!}
            @endif
        </div>
    </div>
    <div class="col-lg-6">
        <div class="cp-user-copy tabcontent-right">
            <form action="#">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <button type="button" class="copy_to_clip btn">{{__('Copy')}}</button>
                    </div>
                    <input readonly value="{{isset($wallet_address) ? $wallet_address->address : 0}}"
                           type="text" class="form-control" id="addressVal">
                </div>
            </form>
            <div class="aenerate-address">
                @if(empty($wallet_address) || empty($wallet_address->address))
                    <a class="btn cp-user-buy-btn"  href="{{route('generateNewAddress')}}?wallet_id={{$wallet->id}}">
                        {{__('Generate address')}}
                    </a>
                @endif
            </div>
        </div>
        <div class="card mt-4">
            <h5 class="card-header">{{__("Token Info")}}</h5>
            <div class="card-body">
                <p> <label for="">{{__('Chain link')}} : </label></p>
                <p>
                    <label for="">{{allsetting('chain_link')}}</label>
                </p>
                <p><label for="">{{__('Contract address')}} :</label></p>
                <p>
                    <label for="">
                        {{allsetting('contract_address')}}
                    </label>
                </p>
                <p><label for="">{{__('Token Symbol')}} :</label></p>
                <p>
                    <label for="">
                        {{isset(allsetting()['coin_name']) ? allsetting()['coin_name'] : ''}}
                    </label>
                </p>
            </div>
        </div>
    </div>
</div>
