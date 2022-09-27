@extends('user.master',['menu'=>'pocket','sub_menu'=>'my_pocket'])
@section('title', isset($title) ? $title : '')
@section('style')
    <style>
        .address-pagin ul.pagination li.page-item:not(:last-child) {
            margin-right: 5px;
        }

        .address-pagin ul.pagination .page-item .page-link {
            color: #fff;
            background: transparent;
            border: none;
            font-size: 16px;
            width: 30px;
            height: 30px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .address-pagin ul.pagination .page-item:hover .page-link,
        .address-pagin ul.pagination .page-item.active .page-link {
            background: linear-gradient(to bottom, #3e4c8d 0%, #4254a5 100%);
            border-radius: 2px;
        }
    </style>
@endsection
@section('content')
    <div class="card cp-user-custom-card cp-user-deposit-card">
        <div class="row">
            <div class="col-sm-12">
                <div class="wallet-inner">
                    <div class="wallet-content card-body">
                        <div class="wallet-top cp-user-card-header-area">
                            <div class="title">
                                <div class="wallet-title text-center">
                                    <h4>{{$wallet->name}}</h4>
                                </div>
                            </div>
                            <div class="tab-navbar">
                                <div class="tabe-menu">
                                    <ul class="nav cp-user-profile-nav mb-0" id="myTab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link wallet {{($active == 'deposit') ? 'active' : ''}}"
                                               id="diposite-tab"
                                               href="{{route('walletDetails',$wallet->id)}}?q=deposit"
                                               aria-controls="diposite" aria-selected="true">
                                                <i class="flaticon-wallet"></i> {{__('Deposit')}}
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link send  {{($active == 'withdraw') ? 'active' : ''}}"
                                               id="withdraw-tab"
                                               href="{{route('walletDetails',$wallet->id)}}?q=withdraw"
                                               aria-controls="withdraw" aria-selected="false">
                                                <i class="flaticon-send"> </i> {{__('Withdraw')}}
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link share  {{($active == 'activity') ? 'active' : ''}}"
                                               id="activity-tab"
                                               href="{{route('walletDetails',$wallet->id)}}?q=activity"
                                               aria-controls="activity" aria-selected="false">
                                                <i class="flaticon-share"> </i> {{__('Activity log')}}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade   {{($active == 'deposit') ? 'show active' : ''}} in"
                                 id="diposite" role="tabpanel"
                                 aria-labelledby="diposite-tab">
                                @include('user.pocket.include.deposit')
                            </div>
                            <div class="tab-pane fade {{($active == 'withdraw') ? 'show active' : ''}} in" id="withdraw"
                                 role="tabpanel" aria-labelledby="withdraw-tab">
                                @include('user.pocket.include.withdrawal')
                            </div>
                            <div class="tab-pane fade  {{($active == 'activity') ? 'show active' : ''}} in"
                                 id="activity" role="tabpanel" aria-labelledby="activity-tab">
                                @include('user.pocket.include.activity')
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
        function withDrawBalance() {
            var g2fCheck = '{{\Illuminate\Support\Facades\Auth::user()->google2fa_secret}}';


            if (g2fCheck.length > 1) {
                var frm = $('#withdrawFormData');

                $.ajax({
                    type: frm.attr('method'),
                    url: frm.attr('action'),
                    data: frm.serialize(),
                    success: function (data) {
                        console.log(data.success);
                        if (data.success == true) {
                            $('#g2fcheck').modal('show');

                        } else {
                            VanillaToasts.create({
                                // title: 'Message Title',
                                text: data.message,
                                type: 'warning',
                                timeout: 3000

                            });
                        }

                    },
                    error: function (data) {

                    },
                });
            } else {
                VanillaToasts.create({
                    // title: 'Message Title',
                    text: "{{__('Your google authentication is disabled,please enable it')}}",
                    type: 'warning',
                    timeout: 3000

                });
            }

        }
    </script>
    <script>


        document.querySelector('button').addEventListener('click', function (event) {

            var copyTextarea = document.querySelector('#address');
            copyTextarea.focus();
            copyTextarea.select();

            try {
                var successful = document.execCommand('copy');
                VanillaToasts.create({
                    // title: 'Message Title',
                    text: '{{__('Address copied successfully')}}',
                    type: 'success',

                });
            } catch (err) {

            }
        });

        function generateNewAddress() {
            $.ajax({
                type: "GET",
                enctype: 'multipart/form-data',
                url: "{{route('generateNewAddress')}}?wallet_id={{$wallet_id}}",
                success: function (data) {
                    if (data.success == true) {

                        $('#address').val(data.address);
                        var srcVal = "{{route('qrCodeGenerate')}}?address=" + data.address;
                        document.getElementById('qrcode').src = srcVal;
                        VanillaToasts.create({
                            // title: 'Message Title',
                            text: data.message,
                            type: 'success',
                            timeout: 3000

                        });
                        $('#qrcode').src(data.qrcode);
                    } else {

                        VanillaToasts.create({
                            // title: 'Message Title',
                            text: data.message,
                            type: 'warning',
                            timeout: 3000

                        });

                    }
                }
            });
        }
    </script>

    {{--    <script>--}}
    {{--        function call_coin_rate(amount) {--}}
    {{--            $.ajax({--}}
    {{--                type: "POST",--}}
    {{--                url: "{{ route('withdrawCoinRate') }}",--}}
    {{--                data: {--}}
    {{--                    '_token': "{{ csrf_token() }}",--}}
    {{--                    'amount': amount,--}}
    {{--                    'wallet_id': "{{$wallet_id}}",--}}
    {{--                },--}}
    {{--                dataType: 'JSON',--}}

    {{--                success: function (data) {--}}
    {{--                    console.log(data);--}}
    {{--                    $('.totalBTC').text(data.btc_dlr);--}}
    {{--                    $('.coinType').text(data.coin_type);--}}
    {{--                },--}}
    {{--                error: function () {--}}
    {{--                }--}}
    {{--            });--}}
    {{--        }--}}
    {{--    </script>--}}

    {{--    <script>--}}
    {{--        function delay(callback, ms) {--}}
    {{--            var timer = 0;--}}
    {{--            return function () {--}}
    {{--                var context = this, args = arguments;--}}
    {{--                clearTimeout(timer);--}}
    {{--                timer = setTimeout(function () {--}}
    {{--                    callback.apply(context, args);--}}
    {{--                }, ms || 0);--}}
    {{--            };--}}
    {{--        }--}}

    {{--        function call_coin_payment() {--}}
    {{--            var amount = $('input[name=amount]').val();--}}
    {{--            call_coin_rate(amount);--}}
    {{--        }--}}

    {{--        $("#amount").keyup(delay(function (e) {--}}
    {{--            var amount = $('input[name=amount]').val();--}}
    {{--            call_coin_rate(amount);--}}
    {{--            console.log(amount);--}}

    {{--        },500));--}}

    {{--    </script>--}}
    <!-- copy_to_clip -->
    <script>
        $('.copy_to_clip').on('click', function () {
            /* Get the text field */
            var copyFrom = document.getElementById("address");

            /* Select the text field */
            copyFrom.select();

            /* Copy the text inside the text field */
            document.execCommand("copy");

            VanillaToasts.create({
                title: 'Copied the text',
                // text: copyFrom.value,
                type: 'success',
                timeout: 3000,
                positionClass: 'topCenter'
            });
        })
    </script>
@endsection
