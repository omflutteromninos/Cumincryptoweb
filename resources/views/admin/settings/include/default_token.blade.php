<div class="header-bar">
    <div class="table-title">
        <h3>{{__('Default Coin Send Settings')}}</h3>
    </div>
</div>
<div class="profile-info-form">
    <form method="post" action="{{route('adminWithdrawalSettings')}}">
        @csrf
        <div class="row">
            <div class="col-lg-6 col-12  mt-20">
                <div class="form-group">
                    <label for="#">{{__('Default Coin/Token Name')}}</label>
                    <input class="form-control" type="text" name="coin_name"
                           placeholder="{{__('Default coin or token name')}}" value="{{$settings['coin_name']}}">
                </div>
            </div>
            <div class="col-lg-6 col-12  mt-20">
                <div class="form-group">
                    <label for="#">{{__('Default Token price (in USD)')}}</label>
                    <input class="form-control number_only" type="text" name="coin_price"
                           placeholder="{{__('Default token price')}}"
                           value="{{isset($settings['coin_price']) ? $settings['coin_price'] : ''}}">
                </div>
            </div>
            <div class="col-lg-6 col-12  mt-20">
                <div class="form-group">
                    <label for="#">{{__('Base Coin Name For Token')}}</label>
                    <input class="form-control" type="text" name="contract_coin_name"
                           placeholder="{{__('Base Coin Name For Token Ex. ETH/BNB')}}"
                           value="{{$settings['contract_coin_name'] ?? 'ETH'}}">
                </div>
            </div>
            <div class="col-lg-6 col-12 mt-20">
                <div class="form-group">
                    <label for="#">{{__('Chain link')}}</label>
                    <input class="form-control" type="text" name="chain_link" required
                           placeholder="" value="{{$settings['chain_link'] ?? ''}}">
                </div>
            </div>
            <div class="col-lg-6 col-12 mt-20">
                <div class="form-group">
                    <label for="#">{{__('Chain ID')}}</label>
                    <input class="form-control" type="text" name="chain_id" required
                           placeholder="" value="{{$settings['chain_id'] ?? ''}}">
                </div>
            </div>
            <div class="col-lg-6 col-12 mt-20">
                <div class="form-group">
                    <label for="#">{{__('Contract Address')}}</label>
                    <input class="form-control" type="text" name="contract_address" required
                           placeholder="" value="{{$settings['contract_address'] ?? ''}}">
                </div>
            </div>
            <div class="col-lg-6 col-12 mt-20">
                <div class="form-group">
                    <label for="#">{{__('Wallet address')}}</label>
                    <input class="form-control" type="text" required name="wallet_address"
                           placeholder="" value="{{$settings['wallet_address'] ?? ''}}">
                </div>
            </div>
            <div class="col-lg-6 col-12 mt-20">
                <div class="form-group">
                    <label for="#">{{__('Private key')}}</label>
                    <input class="form-control" type="text" required name="private_key"
                           placeholder="" value="{{$settings['private_key'] ?? ''}}">
                </div>
            </div>
            <div class="col-lg-6 col-12 mt-20">
                <div class="form-group">
                    <label for="#">{{__('Decimal')}}</label>
                    <select name="contract_decimal" id="" class="form-control">
                        @foreach(contract_decimals() as $key => $val)
                            <option @if(isset($settings['contract_decimal']) && $settings['contract_decimal'] == $key) selected @endif
                            value="{{$key}}">{{$key}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-6 col-12 mt-20">
                <div class="form-group">
                    <label for="#">{{__('Gas Limit')}}</label>
                    <input type="text" name="gas_limit" class="form-control"
                           @if(isset($settings['gas_limit'])) value="{{$settings['gas_limit']}}" @else value="216200" @endif>
                </div>
            </div>
            <div class="col-lg-12 col-12 mt-20">
                <div class="form-group">
                    <label for="#" class="text-warning">{{__('Note: Maximum Withdrawal and minimum withdrawal setting or coin related setting will find in coin update page . Here you can set the setting for only default coin')}}</label>
                </div>
            </div>
            <div class="col-lg-6 col-12 mt-20">
                <div class="form-group">
                    <label for="#">{{__('Max. Send Limit (per day)')}}</label>
                    <input type="text" class="form-control" name="max_send_limit"
                           placeholder="{{__('Send Limit')}}"
                           value="{{$settings['max_send_limit']}}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 col-12 mt-20">
                <button type="submit" class="btn">{{__('Update')}}</button>
            </div>
        </div>
    </form>
</div>
