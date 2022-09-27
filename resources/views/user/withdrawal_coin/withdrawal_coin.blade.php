@extends('user.master',['menu'=>'coin_request', 'sub_menu'=>'withdrawal_coin'])
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
                                    <h4>{{__('Withdrawal Default Coin')}}</h4>
                                    <small class="text-warning">{{__('Note: Here you can withdrawal default coin only using metamask')}}</small>
                                </div>
                            </div>
                            <div class="clap-wrap mt-5">

                                <ul class="nav nav-pills transfer-tabs my-3" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link @if($qr == 'requests') active @endif" id="pills-transfer-1-tab" data-toggle="pill"
                                           href="#pills-transfer-1" role="tab" aria-controls="pills-transfer-1"
                                           aria-selected="true">{{__('Default Coin withdrawal')}}</a>
                                    </li>

                                </ul>
                                <div class="tab-content" id="pills-tabContent">
                                    <div class="tab-pane fade @if($qr == 'requests') show active @endif" id="pills-transfer-1" role="tabpanel"
                                         aria-labelledby="pills-transfer-1-tab">
                                        <div class="cp-user-card-header-area d-block">
                                            <div class="cp-user-title">
                                                <h4>{{__('withdrawal your default coin')}}</h4>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="cp-user-profile-info">
                                                        <form class="mt-4" action="">
                                                            @csrf
                                                            <input type="hidden" id="temp" value="">

                                                            <div class="form-group mt-4">
                                                                <label>{{__('Coin Amount')}}</label>
                                                                <input name="amount"  type="" id="amount" placeholder="{{__('Coin')}}"
                                                                       class="form-control number_only confirm" value="{{old('amount')}}">
                                                                <span class="text-warning" style="font-weight: 700;">{{__('Minimum amount : ')}}</span>
                                                                <span class="text-warning">{{$wallet->minimum_withdrawal}} {{settings('coin_name')}}</span>
                                                                <span class="text-warning">{{__(' and ')}}</span>
                                                                <span class="text-warning" style="font-weight: 700;">{{__('Maximum amount : ')}}</span>
                                                                <span class="text-warning">{{number_format($wallet->maximum_withdrawal,2)}} {{settings('coin_name')}}</span>
                                                            </div>
                                                            <div class="form-group m4">
                                                                <label>{{__('Address')}}</label>
                                                                <input name="address"  type="" placeholder="{{__('address')}}" id="address"
                                                                       class="form-control " value="">
                                                            </div>
                                                            <div class="form-group m-0">
                                                                <button onclick="withdrwalAmount(this)" class="btn theme-btn confirm"
                                                                        type="button">{{__('Confirm')}}</button>
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
    </div>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bignumber.js/8.0.2/bignumber.min.js" integrity="sha512-7UzDjRNKHpQnkh1Wf1l6i/OPINS9P2DDzTwQNX79JxfbInCXGpgI1RPb3ZD+uTP3O5X7Ke4e0+cxt2TxV7n0qQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/web3/1.5.1/web3.min.js" integrity="sha512-8Frac7ZdCMHBsKch6t/XEAKauXT1PXTgRGX/9NO3IzfLQ3QlTnr8ACRmJMOWPr3rxeCFpjUH+Hk7Y4v4zm825Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{asset('js/abi.js')}}"></script>
    <script src="{{asset('js/chain.js')}}"></script>
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
