<div class="row">
    <div class="col-lg-4 offset-lg-1">
        <div class="qr-img text-center">
            @if(!empty($address))  {!! QrCode::size(300)->generate($address); !!}
            @else
                {!! QrCode::size(300)->generate(0); !!}
            @endif
        </div>
    </div>
    <div class="col-lg-6">
        <div class="cp-user-copy tabcontent-right">
            <form action="#" class="px-3">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <button type="button"
                                class="copy_to_clip btn">{{__('Copy')}}</button>
                    </div>
                    <input readonly value="{{isset($address) ? $address : 0}}"
                           type="text" class="form-control" id="address">
                </div>
            </form>
            <div class="aenerate-address">
                <a class="btn cp-user-buy-btn"
                   href="{{route('generateNewAddress')}}?wallet_id={{$wallet_id}}">
                    {{__('Generate a new address')}}
                </a>
            </div>
            <div class="show-post">
                <button class="btn cp-user-buy-btn"
                        onclick="$('.address-list').toggleClass('show');">Show past
                    address
                </button>
                <div class="address-list">
                    <div class="cp-user-wallet-table table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>{{__('Address')}}</th>
                                <th>{{__('Created At')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($address_histories as $address_history)
                                <tr>
                                    <td>{{$address_history->address}}</td>
                                    <td>{{$address_history->created_at}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>


                        @if(isset($address_histories[0]))
                            <div class="pull-right address-pagin">
                                {{ $address_histories->appends(request()->input())->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
