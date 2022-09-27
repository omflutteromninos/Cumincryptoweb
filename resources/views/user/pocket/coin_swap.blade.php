@extends('user.master',['menu'=>'coin_swap','sub_menu'=>'swap_history'])
@section('title', isset($title) ? $title : __('Convert History'))
@section('style')
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12 mb-xl-0 mb-4">
            <div class="card cp-user-custom-card">
                <div class="card-body">
                    <div class="cp-user-card-header-area">
                        <h4>{{__('Coin Swap')}}</h4>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="cp-user-buy-coin-content-area">
                                <div class="cp-user-coin-info">
                                    <div class="form-group">
                                        <label>{{__('Select from coin wallet')}}</label>
                                        <div class="cp-select-area">
                                            <select name=""
                                                    class=" form-control "
                                                    id="payment_type">
                                                <option value="">{{__('Select')}}</option>
                                                @if(isset($wallets[0]))
                                                    @foreach($wallets as $wallet)
                                                        <option value="{{$wallet->id}}">
                                                            {{$wallet->name}}({{ check_default_coin_type($wallet->coin_type) }})
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
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
    <div class="swipe-area">
        <div class="menu-close">
            <i class="fa fa-times"></i>
        </div>
        <div class="header-top">
            <h2>{{__('Swap Coin')}}</h2>
        </div>
        <div class="swipe-inner">
            <div class="swap_info"></div>
            <div class="menu-select">
                <div class="coin-menu swap_coin_data">
                </div>
            </div>
            <div class="next-btn">
                {{--                    <button type="button" data-toggle="modal" data-target="#confirm_swap" class="next_step" >{{__('Next')}}</button>--}}
                <button type="button"  class="next_step" >{{__('Next')}}</button>
            </div>
        </div>
    </div>
    <div class="swipe-area-overlay"></div>

    <div class="modal fade" id="confirm_swap" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <img src="{{asset('assets/user/images/close.svg')}}" class="img-fluid" alt="">
                </button>
                <div class="text-center">
                    <img src="{{asset('assets/user/images/add-pockaet-vector.svg')}}" class="img-fluid img-vector" alt="">
                    <h3>{{__('Do you want to swap coin?')}}</h3>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{route('swapCoin')}}" id="swap_coin_form">
                        @csrf
                        <input type="hidden" id="input_from_coin_id" name="from_coin_id" >
                        <input type="hidden" id="input_to_coin_id" name="to_coin_id" >
                        <input type="hidden" id="input_amount" name="amount" >
                        <button type="submit" class="btn btn-block cp-user-move-btn">{{__('Swap Coin')}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function(){
            $(document).on('click','.swip-item', function(){
                var swipItem = $(this).html();
                $(this).empty().html($('.swip-button').html());
                $('.swip-button').empty().html(swipItem);

            });

            $('.coin-menu').find('ul').addClass('sh');
            $('.coin-menu').find('ul').addClass('sh');

            $(document).on('click','.swip-button, .swip-item', function(){
                $('.coin-menu').find('ul').toggleClass('sh');
            })
        });

        var to_coin = '';

        $(document).ready(function(){
            $('#payment_type').change(function() {
                $('body').toggleClass('_toggle');
                let id = $(this).val();
                to_coin = id;
                from_wallet_id = id;
                $('.swap_info').html('');

                $.ajax({
                    url: "{{route('getCoinSwapDetails')}}",
                    data: {
                        'id' : id,
                    },
                    dataType: 'JSON',
                    type: 'GET',
                    success: function (data){
                        console.log(data);
                        $('.swap_coin_data').html(data);
                    },
                    error: function (){

                    }
                })
            });
        });
        function getRate(from_wallet_id, to_wallet_id, amount)
        {
            if(Number(amount)<0){
                $("#from-amount").val(0);
                $("#to-amount").addClass("d-none");
                return alert('Amount must be greater than zero');
            }else{
                $("#to-amount").addClass("d-none");
                $(".loader").removeClass("d-none");
                $.ajax({
                    url: "{{route('getRate')}}",
                    data: {
                        'from_coin_id' : from_wallet_id,
                        'to_coin_id' : to_wallet_id,
                        'amount' : amount
                    },
                    dataType: "JSON",
                    type: "GET",
                    success: function(data){
                        // let swap_data = coinSwapInfo(to_coin,from_coin_type, data, 0);
                        $('.swap_info').html(data);
                        rate = data;
                        $("#to-amount").removeClass("d-none");
                        $(".loader").addClass("d-none");
                    },
                    error: function(){

                    }
                });
            }
        }
        $(document).on('click','.swip-item', function () {
            let from_coin_type = $(this).data('from_coin_type');
            let to_id_wallet = $(this).data('to_wallet_id');

            to_coin_type = from_coin_type;
            to_wallet_id = to_id_wallet;

            $('.swap_info').html(`<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: none; display: block; shape-rendering: auto;" width="257px" height="257px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
            <circle cx="50" cy="50" fill="none" stroke="#ffffff" stroke-width="1" r="10" stroke-dasharray="47.12388980384689 17.707963267948966">
            <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="0.98s" values="0 50 50;360 50 50" keyTimes="0;1"/>
            </circle></svg>`);

            getRate(to_coin, to_wallet_id, 1);

        });

        $(function() {

            function swipeFunc(tomount, toCoinName, fromamount, fromCoinName){
                $('#to-amount').html(fromamount);
                $('#to-coin-name').html(fromCoinName);
                $('#from-amount').val(tomount);
                $('#from-coin-name').html(toCoinName);
            }

            $(document).on('click', '.swipe-btn', function () {
                let from_amount_data = $('#from-amount').val();
                let from_wallet_id = $('#from-wallet-id').val();
                let from_coin_type = $('#from-coin-name').text();

                let to_amount_data = $('#to-amount').text();
                let to_wallet_id = $('#to-wallet-id').val();
                let to_coin_type = $('#to-coin-name').text();

                $('#from-amount').val(to_amount_data);
                $('#from-wallet-id').val(to_wallet_id);
                $('#from-coin-name').text(to_coin_type);

                $('#to-amount').text(from_amount_data);
                $('#to-wallet-id').val(from_wallet_id);
                $('#to-coin-name').text(from_coin_type);
            })
        });

        $(function() {
            $(document).on('input', '#from-amount', function() {
                var from_wallet_id = $('#from-wallet-id').val();
                var to_wallet_id = $('#to-wallet-id').val();
                var amount = $('#from-amount').val();

                getRate(from_wallet_id, to_wallet_id, amount);
            })
        });

        $(function (){
            $(document).on('click', '.next_step', function () {
                $('#confirm_swap').modal('show');

                $('#swap_coin_form input[name=from_coin_id]').val($('#from-wallet-id').val());
                $('#swap_coin_form input[name=to_coin_id]').val($('#to-wallet-id').val());
                $('#swap_coin_form input[name=amount]').val($('#from-amount').val());
            })
        })
    </script>
@endsection
