@extends('user.master',['menu'=>'coin_request', 'sub_menu'=>'give_coin'])
@section('title', isset($title) ? $title : '')
@section('style')
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card cp-user-custom-card cp-user-wallet-card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="cp-user-card-header-area">
                                <div class="cp-user-title">
                                    <h4>{{__('Send/Request Default Coin')}}</h4>
                                    <small class="text-warning">{{__('Note: Here you can send or receive default coin only. To send other coin go to pocket menu')}}</small>
                                </div>
                            </div>
                            <div class="clap-wrap mt-5">

                                <ul class="nav nav-pills transfer-tabs my-3" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link @if($qr == 'requests') active @endif" id="pills-transfer-1-tab" data-toggle="pill"
                                           href="#pills-transfer-1" role="tab" aria-controls="pills-transfer-1"
                                           aria-selected="true">{{__('Default Coin Request')}}</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link @if($qr == 'give') active @endif" id="pills-transfer-2-tab" data-toggle="pill"
                                           href="#pills-transfer-2" role="tab" aria-controls="pills-transfer-2"
                                           aria-selected="false">{{__('Send Coin')}}</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="pills-tabContent">
                                    <div class="tab-pane fade @if($qr == 'requests') show active @endif" id="pills-transfer-1" role="tabpanel"
                                         aria-labelledby="pills-transfer-1-tab">
                                        <div class="cp-user-card-header-area d-block">
                                            <div class="cp-user-title">
                                                <h4>{{__('Send Default Coin Request To User Using Email Address')}}</h4>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="cp-user-profile-info">
                                                        <form class="mt-4" method="POST"
                                                              action="{{route('sendCoinRequest')}}">
                                                            @csrf
                                                            <div class="form-group mt-4">
                                                                <label>{{__('User Email')}}</label>
                                                                <input name="email" type="email" placeholder="{{__('User Email')}}"
                                                                       class="form-control " value="{{old('email')}}">
                                                                <span class="text-warning" style="font-weight: 700;">{{__('Note : ')}}</span>
                                                                <span class="text-warning">{{__('Input user email where you want to send request for coin.')}}</span>
                                                            </div>
                                                            <div class="form-group mt-4">
                                                                <label>{{__('Coin Amount')}}</label>
                                                                <input name="amount" type="text" placeholder="{{__('Coin')}}"
                                                                       class="form-control number_only" value="{{old('amount')}}">
                                                                <span class="text-warning" style="font-weight: 700;">{{__('Minimum amount : ')}}</span>
                                                                <span class="text-warning">{{$coin->minimum_withdrawal}} {{settings('coin_name')}}</span>
                                                                <span class="text-warning">{{__(' and ')}}</span>
                                                                <span class="text-warning" style="font-weight: 700;">{{__('Maximum amount : ')}}</span>
                                                                <span class="text-warning">{{$coin->maximum_withdrawal}} {{settings('coin_name')}}</span>
                                                            </div>
                                                            <div class="form-group m-0">
                                                                <button class="btn theme-btn"
                                                                        type="submit">{{__('Send Request')}}</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade @if($qr == 'give') show active @endif" id="pills-transfer-2" role="tabpanel"
                                         aria-labelledby="pills-transfer-2-tab">

                                        <div class="cp-user-card-header-area">
                                            <div class="cp-user-title">
                                                <h4>{{__('Send Coin To User From Your Default Coin Wallet To User Primary Default Coin Wallet Using Email Address')}}</h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="cp-user-profile-info">
                                                    <form class="mt-4" method="POST" action="{{route('giveCoin')}}"
                                                          onsubmit="return submitResult();">
                                                        @csrf
                                                        <div class="form-group mt-4">
                                                            <label>{{__('Select Your Default Coin Wallet')}}</label>
                                                            <div class="cp-select-area">
                                                                <select name="wallet_id" class="form-control" id="">
                                                                    <option value="">{{__('Select')}}</option>
                                                                    @if(isset($wallets[0]))
                                                                        @foreach($wallets as $wallet)
                                                                            <option value="{{$wallet->id}}"> {{$wallet->name}}
                                                                                ({{number_format($wallet->balance,2)}})
                                                                            </option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group mt-4">
                                                            <label>{{__('Coin Amount')}}</label>
                                                            <input name="amount" type="text" placeholder="{{__('Coin')}}"
                                                                   class="form-control">
                                                            <span class="text-warning" style="font-weight: 700;">{{__('Minimum send amount : ')}}</span>
                                                            <span class="text-warning">{{$coin->minimum_withdrawal}} {{settings('coin_name')}}</span>
                                                            <span class="text-warning">{{__(' and ')}}</span>
                                                            <span class="text-warning" style="font-weight: 700;">{{__('Maximum send amount : ')}}</span>
                                                            <span class="text-warning">{{$coin->maximum_withdrawal}} {{settings('coin_name')}}</span>
                                                        </div>
                                                        <div class="form-group mt-4">
                                                            <label>{{__('User Email')}}</label>
                                                            <input name="email" type="email" placeholder="{{__('User Email')}}"
                                                                   class="form-control ">
                                                            <span class="text-warning" style="font-weight: 700;">{{__('Note : ')}}</span>
                                                            <span class="text-warning">{{__('Input user email where you want to send coin. Coin will send to his/her primary wallet.')}}</span>
                                                        </div>

                                                        <div class="form-group m-0">
                                                            <button class="btn btn-info theme-btn"
                                                                    type="submit">{{__('Send Coin')}}</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function submitResult() {
            if (confirm("Are you sure to send coin?") == false) {
                return false;
            } else {
                return true;
            }
        }
    </script>
@endsection
