@extends('user.master',['menu'=>'pocket','sub_menu'=>'my_pocket'])
@section('title', isset($title) ? $title : '')
@section('style')
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card cp-user-custom-card cp-user-wallet-card">
                <div class="card-body">
                    <div class="cp-user-card-header-area">
                        <div class="cp-user-title">
                            <h4>{{__('My Pocket')}}</h4>
                        </div>
                        <div class="buttons">
                            <button class="btn cp-user-add-pocket" data-toggle="modal"
                                    data-target="#add-pocket">{{__('Add Pocket')}}</button>
                            @if(co_wallet_feature_active())
                                <button class="btn cp-user-add-pocket" data-toggle="modal"
                                        data-target="#import-pocket">{{__('Import  Multi-signature Pocket')}}</button>
                            @endif
                        </div>
                    </div>

                    @if(co_wallet_feature_active())
                    <div class="clap-wrap mt-5">
                        <ul class="nav nav-pills transfer-tabs my-3" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link @if(!isset($tab) || $tab=='personal-pocket') active @endif" id="personal-pocket-tab"
                                   data-toggle="pill"
                                   href="#personal-pocket" role="tab" aria-controls="personal-pocket"
                                   aria-selected="true">{{__('Personal Pockets')}}</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link @if(isset($tab) && $tab=='co-pocket') active @endif" id="co-pocket-tab"
                                   data-toggle="pill"
                                   href="#co-pocket" role="tab" aria-controls="co-pocket"
                                   aria-selected="false">{{__(' Multi-signature Pockets')}}</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade @if(!isset($tab) || $tab=='personal-pocket') show active @endif" id="personal-pocket"
                                 role="tabpanel"
                                 aria-labelledby="personal-pocket-tab">
                                @endif
                                <div class="cp-user-wallet-table table-responsive">
                                    <table class="table table-borderless cp-user-custom-table" width="100%">
                                        <thead>
                                        <tr>
                                            <th class="all">{{__('Name')}}</th>
                                            <th class="all">{{__('Coin Type')}}</th>
                                            <th class="desktop">{{__('Balance')}}</th>
{{--                                            <th class="desktop">{{__('Referral Balance')}}</th>--}}
                                            <th class="desktop">{{__('Updated At')}}</th>
                                            <th class="all">{{__('Action')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($wallets[0]))
                                            @foreach($wallets as $wallet)
                                                <tr>
                                                    <td>{{ $wallet->name }}</td>
                                                    <td>{{ check_default_coin_type($wallet->coin_type) }}</td>
                                                    <td>{{ $wallet->balance }}</td>
{{--                                                    <td>{{ $wallet->referral_balance }}</td>--}}
                                                    <td>{{ $wallet->updated_at }}</td>
                                                    <td>
                                                        <ul class="d-flex justify-content-center align-items-center">
                                                            @if(is_primary_wallet($wallet->id, $wallet->coin_type) == 0)
                                                                <li>
                                                                    <a title="{{__('Make primary')}}"
                                                                       href="{{route('makeDefaultAccount',[$wallet->id, $wallet->coin_type])}}">
                                                                        <img
                                                                            src="{{asset('assets/user/images/wallet-table-icons/Key.svg')}}"
                                                                            class="img-fluid" alt="">
                                                                    </a>
                                                                </li>
                                                            @endif

                                                            <li>
                                                                <a title="{{__('Deposit')}}"
                                                                   href="{{route('walletDetails',$wallet->id)}}?q=deposit">
                                                                    <img
                                                                        src="{{asset('assets/user/images/wallet-table-icons/wallet.svg')}}"
                                                                        class="img-fluid" alt="">
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a title="{{__('withdraw')}}"
                                                                   href="{{route('walletDetails',$wallet->id)}}?q=withdraw">
                                                                    <img
                                                                        src="{{asset('assets/user/images/wallet-table-icons/send.svg')}}"
                                                                        class="img-fluid" alt="">
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a title="{{__('Activity log')}}"
                                                                   href="{{route('walletDetails',$wallet->id)}}?q=activity">
                                                                    <img
                                                                        src="{{asset('assets/user/images/wallet-table-icons/share.svg')}}"
                                                                        class="img-fluid" alt="">
                                                                </a>
                                                            </li>
                                                            @if(getSwapStatus())
                                                                @if($wallet->coin_type != DEFAULT_COIN_TYPE)
                                                                    <li class="menu-toggler">
                                                                        <a title="{{__('Swap')}}"
                                                                           data-to_coin_type="{{$wallet->coin_type}}"
                                                                           data-from_wallet_id="{{$wallet->id}}">
                                                                            <img
                                                                                src="{{asset('assets/user/images/wallet-table-icons/swap.svg')}}"
                                                                                class="img-fluid" alt="Swap">
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                            @endif
                                                        </ul>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>

                                @if(co_wallet_feature_active())
                            </div>
                            <div class="tab-pane fade @if(isset($tab) && $tab=='co-pocket') show active @endif" id="co-pocket"
                                 role="tabpanel"
                                 aria-labelledby="co-pocket-tab">
                                <div class="cp-user-wallet-table table-responsive">
                                    <table class="table table-borderless cp-user-custom-table" width="100%">
                                        <thead>
                                        <tr>
                                            <th class="all">{{__('Name')}}</th>
                                            <th class="all">{{__('Key')}}</th>
                                            <th class="all">{{__('Coin Type')}}</th>
                                            <th class="desktop">{{__('Balance')}}</th>
{{--                                            <th class="desktop">{{__('Referral Balance')}}</th>--}}
                                            <th class="desktop">{{__('Updated At')}}</th>
                                            <th class="all">{{__('Action')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($coWallets[0]))
                                            @foreach($coWallets as $wallet)
                                                <tr>
                                                    <td>{{ $wallet->name }}</td>
                                                    <td>{{ $wallet->key }}</td>
                                                    <td>{{ check_default_coin_type($wallet->coin_type) }}</td>
                                                    <td>{{ $wallet->balance }}</td>
{{--                                                    <td>{{ $wallet->referral_balance }}</td>--}}
                                                    <td>{{ $wallet->updated_at }}</td>
                                                    <td>
                                                        <ul class="d-flex justify-content-center align-items-center">
                                                            @if($wallet->coin_type == 'Default')
                                                                <li>
                                                                    <a title="{{__('Request Coin')}}"
                                                                       href="{{route('requestCoin')}}">
                                                                        <img
                                                                            src="{{asset('assets/user/images/sidebar-icons/coin.svg')}}"
                                                                            class="img-fluid" alt="">
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a title="{{__('Give Coin')}}"
                                                                       href="{{route('requestCoin')}}?qr=give">
                                                                        <img
                                                                            src="{{asset('assets/user/images/wallet-table-icons/send.svg')}}"
                                                                            class="img-fluid" alt="">
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a title="{{__('Send History')}}"
                                                                       href="{{route('giveCoinHistory')}}">
                                                                        <img
                                                                            src="{{asset('assets/admin/images/sidebar-icons/Transaction-1.svg')}}"
                                                                            class="img-fluid" alt="">
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a title="{{__('Receive History')}}"
                                                                       href="{{route('receiveCoinHistory')}}">
                                                                        <img
                                                                            src="{{asset('assets/admin/images/sidebar-icons/coin.svg')}}"
                                                                            class="img-fluid" alt="">
                                                                    </a>
                                                                </li>
                                                            @else
                                                                <li>
                                                                    <a title="{{__('Co Users')}}"
                                                                       href="{{route('coWalletUsers', $wallet->id)}}">
                                                                        <img
                                                                            src="{{asset('assets/user/images/sidebar-icons/user.svg')}}"
                                                                            class="img-fluid" alt="">
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a title="{{__('Deposit')}}"
                                                                       href="{{route('walletDetails',$wallet->id)}}?q=deposit">
                                                                        <img
                                                                            src="{{asset('assets/user/images/wallet-table-icons/wallet.svg')}}"
                                                                            class="img-fluid" alt="">
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a title="{{__('withdraw')}}"
                                                                       href="{{route('walletDetails',$wallet->id)}}?q=withdraw">
                                                                        <img
                                                                            src="{{asset('assets/user/images/wallet-table-icons/send.svg')}}"
                                                                            class="img-fluid" alt="">
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a title="{{__('Activity log')}}"
                                                                       href="{{route('walletDetails',$wallet->id)}}?q=activity">
                                                                        <img
                                                                            src="{{asset('assets/user/images/wallet-table-icons/share.svg')}}"
                                                                            class="img-fluid" alt="">
                                                                    </a>
                                                                </li>
                                                            @endif
                                                        </ul>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>



    {{-- swipe-area  start--}}

    <form action="">
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
                    <button type="button" class="next_step">{{__('Next')}}</button>
                </div>
            </div>
        </div>
        <div class="swipe-area-overlay"></div>
    </form>

    {{-- swipe-area  end--}}

    <!-- add pocket modal -->
    <div class="modal fade cp-user-move-coin-modal" id="add-pocket" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <img src="{{asset('assets/user/images/close.svg')}}" class="img-fluid" alt="">
                </button>
                <div class="text-center">
                    <img src="{{asset('assets/user/images/add-pockaet-vector.svg')}}" class="img-fluid img-vector"
                         alt="">
                    <h3>{{__('Want To Add New Pocket?')}}</h3>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{route('createWallet')}}" id="walletCreateForm">
                        @csrf
                        @if(co_wallet_feature_active())
                            <div class="form-group">
                                <label>{{__('Pocket Type')}}</label>
                                <select name="type" required class="form-control">
                                    <option value="">{{__('Select pocket type')}}</option>
                                    <option value="{{PERSONAL_WALLET}}">{{__('Personal Pocket')}}</option>
                                    <option value="{{CO_WALLET}}">{{__(' Multi-signature Pocket')}}</option>
                                </select>
                            </div>
                        @endif
                        <div class="form-group">
                            <label>{{__('Pocket Name')}}</label>
                            <input type="text" name="wallet_name" required class="form-control"
                                   placeholder="{{__('Write Your Pocket Name')}}">
                        </div>
                        <div class="form-group">
                            <label>{{__('Coin Type')}}</label>
                            <select name="coin_type" required class="form-control">
                                <option value="">{{__('Select coin type')}}</option>
                                <option value="LTCT"> {{'LTCT'}}</option>
                                @if(isset($coins[0]))
                                    @foreach($coins as $coin)
                                        <option value="{{$coin->type}}"> {{$coin->type}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <button type="submit" class="btn btn-block cp-user-move-btn">{{__('Add Pocket')}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(co_wallet_feature_active())
        <!-- import pocket modal -->
        <div class="modal fade cp-user-move-coin-modal" id="import-pocket" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <img src="{{asset('assets/user/images/close.svg')}}" class="img-fluid" alt="">
                    </button>
                    <div class="text-center">
                        <img src="{{asset('assets/user/images/add-pockaet-vector.svg')}}" class="img-fluid img-vector"
                             alt="">
                        <h3>{{__('Want To Import a  Multi-signature Pocket?')}}</h3>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="{{route('importWallet')}}" id="walletImportForm">
                            @csrf
                            <div class="form-group">
                                <label>{{__('Enter Pocket Key')}}</label>
                                <input type="text" name="key" required class="form-control"
                                       placeholder="{{__('Enter pocket key')}}">
                            </div>
                            <button type="submit" class="btn btn-block cp-user-move-btn">{{__('Submit')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- move coin modal -->
    <div class="modal fade" id="confirm_swap" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <img src="{{asset('assets/user/images/close.svg')}}" class="img-fluid" alt="">
                </button>
                <div class="text-center">
                    <img src="{{asset('assets/user/images/add-pockaet-vector.svg')}}" class="img-fluid img-vector"
                         alt="">
                    <h3>{{__('Do you want to swap coin?')}}</h3>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{route('swapCoin')}}" id="swap_coin_form">
                        @csrf
                        <input type="hidden" id="input_from_coin_id" name="from_coin_id">
                        <input type="hidden" id="input_to_coin_id" name="to_coin_id">
                        <input type="hidden" id="input_amount" name="amount">
                        <button type="submit" class="btn btn-block cp-user-move-btn">{{__('Swap Coin')}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')

    <script>
        var from_wallet_id, to_wallet_id, from_coin_type, to_coin_type, requested_amount, converted_amount, rate;
        const MODES = {NORMAL: 0, REVERSE: 1}
        // vars
        let mode = MODES.NORMAL;

        $(document).ready(function () {
            $(document).on('click', '.swip-item', function () {
                var swipItem = $(this).html();
                $(this).empty().html($('.swip-button').html());
                $('.swip-button').empty().html(swipItem);

            });

            $('.coin-menu').find('ul').addClass('sh');
            $('.coin-menu').find('ul').addClass('sh');

            $(document).on('click', '.swip-button, .swip-item', function () {
                $('.coin-menu').find('ul').toggleClass('sh');
            })
        });

        var to_coin = '';

        function getRate(from_wallet_id, to_wallet_id, amount) {
            $("#to-amount").addClass("d-none");
            $(".loader").removeClass("d-none");
            $.ajax({
                url: "{{route('getRate')}}",
                data: {
                    'from_coin_id': from_wallet_id,
                    'to_coin_id': to_wallet_id,
                    'amount': amount
                },
                dataType: "JSON",
                type: "GET",
                success: function (data) {
                    // let swap_data = coinSwapInfo(to_coin,from_coin_type, data, 0);
                    $('.swap_info').html(data);
                    rate = data;
                    $("#to-amount").removeClass("d-none");
                    $(".loader").addClass("d-none");
                },
                error: function () {

                }
            });
        }

        $(document).on('click', '.swip-item', function () {
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

        $(function () {

            function swipeFunc(tomount, toCoinName, fromamount, fromCoinName) {
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

        $('.menu-toggler').on('click', function () {
            let id = $(this).find('a').data('from_wallet_id');
            let to_coin_type = $(this).find('a').data('to_coin_type');
            to_coin = id;
            from_wallet_id = id;
            from_coin_type = to_coin_type;
            $('.swap_info').html('');

            $.ajax({
                url: "{{route('getCoinSwapDetails')}}",
                data: {
                    'id': id,
                    'coin_type': to_coin_type
                },
                dataType: 'JSON',
                type: 'GET',
                success: function (data) {
                    $('.swap_coin_data').html(data);
                },
                error: function () {

                }
            })
        });

        $(function () {
            $(document).on('input', '#from-amount', function () {
                var from_wallet_id = $('#from-wallet-id').val();
                var to_wallet_id = $('#to-wallet-id').val();
                var amount = $('#from-amount').val();

                getRate(from_wallet_id, to_wallet_id, amount);
            })
        });

        $(function () {
            $(document).on('click', '.next_step', function () {
                $('#confirm_swap').modal('show');

                $('#swap_coin_form input[name=from_coin_id]').val($('#from-wallet-id').val());
                $('#swap_coin_form input[name=to_coin_id]').val($('#to-wallet-id').val());
                $('#swap_coin_form input[name=amount]').val($('#from-amount').val());
            })
        })
    </script>
@endsection
