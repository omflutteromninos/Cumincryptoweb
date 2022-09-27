@extends('user.master',['menu'=>'coin', 'sub_menu'=>'buy_coin'])
@section('title', isset($title) ? $title : '')
@section('style')
    <style type="text/css">
        .panel-title {
            display: inline;
            font-weight: bold;
        }
        .display-table {
            display: table;
        }
        .display-tr {
            display: table-row;
        }
        .display-td {
            display: table-cell;
            vertical-align: middle;
            width: 61%;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-6 mb-xl-0 mb-4">
            <div class="card cp-user-custom-card">
                <div class="card-body">
                    <div class="cp-user-card-header-area">
                        <h4>{{__('Buy Our Coin From Here')}}</h4>
                    </div>
                    <div class="cp-user-buy-coin-content-area">
                        @if($no_phase)
                            <p>
                                <span class="text-danger"><i class="fa fa-exclamation-triangle"></i> {{__('No phase active yet')}}</span>
                                <br>
                                <span>{{__('Now you can buy our regular coin')}}</span>
                            </p>
                        @elseif($activePhase['futurePhase'] == true)
                            <p>
                                <span class="text-warning"> {{__('New Ico Phase will start soon')}}</span> <br>
                                <span>{{__('Now you can buy our regular coin')}}</span>
                            </p>
                        @else
                            @php
                                $phase = $activePhase['pahse_info'];
                                $total_sell = \App\Model\BuyCoinHistory::where('status',STATUS_SUCCESS)->where('phase_id',$phase->id)->sum('coin');
                                $progress_bar = 0;

                                $target = $phase->amount;
                                $unsold = ($target >=  $total_sell ) ? bcsub($target,$total_sell) : 0;
                                if ($target != 0) {
                                  $sale = bcmul(100, $total_sell);
                                  $progress_bar = ceil(bcdiv($sale,$target));
                                }
                            @endphp
                            <p>
                                <span class="text-success">{{__('New Ico Phase are available now')}}</span> <br>
                                <span
                                    class="text-warning">{{__('Now you can get some extra facility  when buy coin')}}</span>
                            </p>

                        @endif
                        <div class="cp-user-coin-info">
                            <form action="{{route('buyCoinProcess')}}" method="POST" enctype="multipart/form-data"
                                  id="buy_coin">
                                @csrf
                                <div class="form-group">
                                    @if(isset($phase))
                                        <input type="hidden" name="phase_id" value="{{$phase->id}}">
                                    @endif
                                    <label>{{__('Coin Amount')}}</label>
                                    <input
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                        name="coin" autocomplete="off" id="amount" class="form-control"
                                        placeholder="{{__('Your Amount')}}">
                                    <ul class="coin_price">
                                        <li>{{$coin_price}} x <span class="coinAmount">1</span> = <span
                                                class="CoinInDoller">{{$coin_price}} </span> USD
                                        </li>
                                        <li>$<span class="CoinInDoller">{{$coin_price}} USD</span> = <span
                                                class="totalBTC">{{$btc_dlr}}</span> <span class="coinType"> {{allsetting('base_coin_type')}}</span>
                                        </li>
                                        @if(isset($phase))
{{--                                            <li><span class="">{{__('Fees')}}</span> = <span--}}
{{--                                                    class="coinFees">0 </span> {{settings('coin_name')}}</li>--}}
                                            <li><span class="">{{__('Bonus')}}</span> = <span
                                                    class="coinBonus">0 </span> {{settings('coin_name')}}</li>
                                        @endif
                                    </ul>
                                </div>
                                <div class="cp-user-payment-type">
                                    <h3>{{__('Payment Type')}}</h3>
                                    @if(isset($settings['payment_method_coin_payment']) && $settings['payment_method_coin_payment'] == 1)
                                        <div class="form-group">
                                            <input type="radio" onclick="call_coin_payment();"
                                                   onchange="$('.payment_method').addClass('d-none');$('.bank-details').addClass('d-none');$('.bank-details').removeClass('d-block'); $('.payment-stripe').addClass('d-none').removeClass('d-block');$('.btc_payment').toggleClass('d-none');$('.normal-btn').addClass('d-block').removeClass('d-none')"
                                                   value="{{BTC}}" id="coin-option" name="payment_type">
                                            <label for="coin-option">{{__('Coin Payment')}}</label>
                                        </div>
                                    @endif
                                    @if(isset($settings['payment_method_bank_deposit']) && $settings['payment_method_bank_deposit'] == 1)
                                        <div class="form-group">
                                            <input type="radio" onclick="call_coin_payment();" value="{{BANK_DEPOSIT}}"
                                                   onchange="$('.payment_method').addClass('d-none');$('.bank-details').addClass('d-block');$('.bank-details').removeClass('d-none');$('.payment-stripe').addClass('d-none').removeClass('d-block');$('.bank_payment').toggleClass('d-none');$('.normal-btn').addClass('d-block').removeClass('d-none')"
                                                   id="f-option" name="payment_type">
                                            <label for="f-option">{{__('Bank Deposit')}}</label>
                                        </div>
                                    @endif
                                    @if(isset($settings['payment_method_stripe']) && $settings['payment_method_stripe'] == 1)
                                        <div class="form-group">
                                            <input type="radio" onclick="call_coin_payment();" value="{{STRIPE}}"
                                                   onchange="$('.normal-btn').addClass('d-none');$('.payment_method').addClass('d-none');$('.bank-details').addClass('d-none');$('.payment-stripe').addClass('d-block');$('.payment-stripe').removeClass('d-none');$('.payment-stripe-div').toggleClass('d-none');"
                                                   id="stripe-option" name="payment_type">
                                            <label for="stripe-option">{{__('Credit Card')}}</label>
                                        </div>
                                    @endif
                                </div>

                                <div class="check-box-list btc_payment payment_method d-none">

                                    <div class="form-group buy_coin_address_input ">
                                        <p>
                                            <span id="coinpayment_address"></span>
                                        </p>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">{{__('Payable Coin')}}</label>
                                                <input class="form-control" disabled type="text"
                                                       oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                                       readonly name="total_price" id="total_price"
                                                       placeholder="Amount">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">{{__('Select')}}</label>
                                                <div class="cp-select-area">
                                                    <select name="payment_coin_type"
                                                            class="selet-im vodiapicker form-control "
                                                            id="payment_type">
                                                        @if(isset($coins[0]))
                                                            @foreach($coins as $key)
                                                                <option
                                                                    value="{{$key->type}}">
                                                                    {{$key->type}}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="check-box-list bank_payment payment_method d-none">
                                    <div class="form-group">
                                        <label>{{__('Select Bank')}}</label>
                                        <div class="cp-select-area">
                                            <select name="bank_id" class="bank-id form-control ">
                                                <option value="">{{__('Select')}}</option>
                                                @if(isset($banks[0]))
                                                    @foreach($banks as $value)
                                                        <option
                                                            @if((old('bank_id') != null) && (old('bank_id') == $value->id)) @endif value="{{ $value->id }}">{{$value->bank_name}}</option>
                                                        <span
                                                            class="text-danger"><strong>{{ $errors->first('bank_id') }}</strong></span>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group buy_coin_address_input mt-4">
                                        <div id="file-upload" class="section-p">
                                            <input type="hidden" name="bank_deposit_id" value="">
                                            <input type="file" placeholder="0.00" name="sleep" value="" id="file"
                                                   ref="file" class="dropify"
                                                   data-default-file="{{asset('assets/img/placeholder-image.png')}}"/>
                                        </div>
                                    </div>

                                </div>

                                <button id="buy_button" type="submit" class="btn normal-btn theme-btn">{{__('Buy Now')}}</button>
                            </form>

                            <div class="payment-stripe payment-stripe-div  d-none card card-body dark-bg2">
                                <form role="form" action="{{ route('buyCoinProcess') }}" method="post" class="require-validation"
                                      data-cc-on-file="false"
                                      data-stripe-publishable-key="{{ isset(settings()['STRIPE_KEY']) ? settings()['STRIPE_KEY'] : ''}}"
                                      id="payment-form">
                                    @csrf
                                    @if(isset($phase))
                                        <input type="hidden" name="phase_id" value="{{$phase->id}}">
                                    @endif
                                    <input type="hidden" name="payment_type" value="{{STRIPE}}">
                                    <input type="hidden" name="coin" value="" id="amountCoin" class="form-control" >
                                    <div class='form-row row'>
                                        <div class='col-12 form-group required'>
                                            <label class='control-label'>{{__('Name on Card')}}</label>
                                            <input class='form-control' size="50" type='text'>
                                        </div>
                                    </div>

                                    <div class='form-row row'>
                                        <div class='col-12 form-group required'>
                                            <label class='control-label'>{{__('Card Number')}}</label>
                                            <input autocomplete='off' size="50" class='form-control card-number' type='text'>
                                        </div>
                                    </div>

                                    <div class='form-row row'>
                                        <div class='col-xs-12 col-md-4 form-group cvc required'>
                                            <label class='control-label'>{{__('CVC')}}</label>
                                            <input autocomplete='off' class='form-control card-cvc' placeholder='ex. 311' size='4' type='text'>
                                        </div>
                                        <div class='col-xs-12 col-md-4 form-group expiration required'>
                                            <label class='control-label'>{{__('Expiration Month')}}</label>
                                            <input class='form-control card-expiry-month' placeholder='MM' size='2' type='text'>
                                        </div>
                                        <div class='col-xs-12 col-md-4 form-group expiration required'>
                                            <label class='control-label'>{{__('Expiration Year')}}</label>
                                            <input class='form-control card-expiry-year' placeholder='YYYY' size='4' type='text'>
                                        </div>
                                    </div>

                                    <div class='form-row row'>
                                        <div class='col-md-12 error form-group hide'>
                                            <div class='alert-danger card-alert'></div>
                                        </div>
                                    </div>

                                    <div class="form-row row">
                                        <div class="col-xs-12">
                                            <button id="buy_button" type="submit" class="btn theme-btn">{{__('Buy Now')}}</button>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card cp-user-custom-card ico-phase-info-list">
                <div class="card-body">
                    @if($no_phase)
                        <div class="cp-user-card-header-area">
                            <h4>{{__("Today’s Coin Rate")}}</h4>
                        </div>
                    @elseif($activePhase['futurePhase'] == true)
                        <div class="cp-user-card-header-area future-ico-phase">
                            <h4 class="mb-3">{{__("New Ico Phase will start soon")}}</h4>
                            <p>Start at  : {{date('d M y', strtotime($activePhase['futureDate']))}}</p>
                        </div>
                    @else
                        <div class="cp-user-card-header-area">
                            <h4>{{__("New Ico Phase is running")}}</h4>
                        </div>
                    @endif
                    <div class="cp-user-coin-rate">
                        @if($no_phase)
                            <ul class="">
                                <li>1 {{ settings('coin_name') }}</li>
                                <li>=</li>
                                <li>{{number_format($coin_price,6)}} USD</li>
                            </ul>

                            <div class="img" id="r-side-img">
                                <img src="{{ asset('assets/user/images/buy-coin-vector.svg') }}" class="img-fluid"
                                     alt="">
                            </div>
                        @elseif($activePhase['futurePhase'] == true)
                            <div id="futurePhase" class="countdown-row">
                                <div class="countdown-section">
                                    <span class="days"></span>
                                    <div class="smalltext">{{__('Days')}}</div>
                                </div>
                                <div class="countdown-section">
                                    <span class="hours"></span>
                                    <div class="smalltext">{{__('Hours')}}</div>
                                </div>
                                <div class="countdown-section">
                                    <span class="minutes"></span>
                                    <div class="smalltext">{{__('Minutes')}}</div>
                                </div>
                                <div class="countdown-section">
                                    <span class="seconds"></span>
                                    <div class="smalltext">{{__('Seconds')}}</div>
                                </div>
                            </div>

                            <ul class="">
                                <li>1 {{ settings('coin_name') }}</li>
                                <li>=</li>
                                <li>{{number_format($coin_price,6)}} USD</li>
                            </ul>
                            <div class="img" id="r-side-img">
                                <img src="{{ asset('assets/user/images/buy-coin-vector.svg') }}" class="img-fluid"
                                     alt="">
                            </div>
                        @else

                            <ul class="ico-phase-ul">
                                <li><p>{{ $phase->phase_name }}</p></li>
                                <li>
                                    <p>{{ __('Phase Rate')}} :</p>
                                    <p>1 {{ settings('coin_name') }} = {{number_format($phase->rate,6)}} USD</p>
                                </li>
{{--                                <li><p>{{ __('Fees Percentage')}} :</p>--}}
{{--                                    <p>{{ $phase->fees }}%</p></li>--}}
                                <li><p>{{ __('Bonus Percentage')}} :</p>
                                    <p>{{ number_format($phase->bonus,2) }}%</p></li>
                                <li><p>{{__('Start at')}} :</p>
                                    <p>{{ date('d M y', strtotime($phase->start_date))}}</p></li>
                                <li><p>{{__('End at')}} : </p>
                                    <p>{{ date('d M y', strtotime($phase->end_date))}}</p></li>
                            </ul>
                            <hr>
                            <h5 class="ico-phase-amount-title">{{ settings('coin_name') }} {{__(' Sales Progress')}}</h5>
                            <ul class="ico-phase-ul ico-phase-amount">
                                <li class="total_sale">
                                    <span>{{__('RAISED AMOUNT')}}</span>
                                    <span>{{number_format($total_sell,2)}} {{ settings('coin_name') }}</span>
                                </li>
                                <li class="total_target">
                                    <span>{{__('TARGET AMOUNT')}}</span>
                                    {{ number_format($target,6) }} {{ settings('coin_name') }}
                                </li>
                            </ul>
                            <div class="ico-phase-progress-bar">
                                <div class="progress" data-toggle="tooltip" data-placement="top"
                                     title="{{__('Raised Amount')}} ({{number_format($progress_bar,6)}} %)">
                                    <div class="progress-bar" role="progressbar" style="width: {{$progress_bar}}%;"
                                         aria-valuenow="{{$progress_bar}}" aria-valuemin="0"
                                         aria-valuemax="100">{{$progress_bar}}%
                                    </div>
                                </div>
                                {{--<div class="progress">
                                    <div class="bar" style="width:{{$progress_bar}}">
                                    <span class="tool-tips">
                                        <p>{{__('Raised Amount')}} ({{$progress_bar}} %)</p>
                                    </span>
                                    </div>
                                </div>--}}
                            </div>
                            <p class="card-text card-text-2 mb-2">
                                <span>{{__("SALES END IN")}}</span>
                                <span>{{date('d M y', strtotime($phase->end_date))}}</span>
                            </p>
                            <div id="clockdiv" class="countdown-row">
                                <div class="countdown-section">
                                    <span class="days"></span>
                                    <div class="smalltext">{{__('Days')}}</div>
                                </div>
                                <div class="countdown-section">
                                    <span class="hours"></span>
                                    <div class="smalltext">{{__('Hours')}}</div>
                                </div>
                                <div class="countdown-section">
                                    <span class="minutes"></span>
                                    <div class="smalltext">{{__('Minutes')}}</div>
                                </div>
                                <div class="countdown-section">
                                    <span class="seconds"></span>
                                    <div class="smalltext">{{__('Seconds')}}</div>
                                </div>
                            </div>

                        @endif
                        <div class="bank-details">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>

    <script type="text/javascript">
        $(function() {
            var $form         = $(".require-validation");
            $('form.require-validation').bind('submit', function(e) {
                var $form         = $(".require-validation"),
                    inputSelector = ['input[type=email]', 'input[type=password]',
                        'input[type=text]', 'input[type=file]',
                        'textarea'].join(', '),
                    $inputs       = $form.find('.required').find(inputSelector),
                    $errorMessage = $form.find('div.error'),
                    valid         = true;
                $errorMessage.addClass('hide');

                $('.has-error').removeClass('has-error');
                $inputs.each(function(i, el) {
                    var $input = $(el);
                    if ($input.val() === '') {
                        $input.parent().addClass('has-error');
                        $errorMessage.removeClass('hide');
                        e.preventDefault();
                    }
                });

                if (!$form.data('cc-on-file')) {
                    e.preventDefault();
                    Stripe.setPublishableKey($form.data('stripe-publishable-key'));
                    Stripe.createToken({
                        number: $('.card-number').val(),
                        cvc: $('.card-cvc').val(),
                        exp_month: $('.card-expiry-month').val(),
                        exp_year: $('.card-expiry-year').val()
                    }, stripeResponseHandler);
                }

            });

            function stripeResponseHandler(status, response) {
                console.log(response);
                if (response.error) {
                    $('.error')
                        .removeClass('hide')
                        .find('.card-alert')
                        .text(response.error.message);
                } else {
                    var amount = $('input[name=coin]').val();
                    $('#amountCoin').val(amount);
                    // token contains id, last4, and card type
                    $('.error')
                        .addClass('hide')
                        .find('.card-alert')
                    var token = response['id'];
                    // insert the token into the form so it gets submitted to the server
                    $form.find('input[type=text]').empty();
                    $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                    $form.get(0).submit();
                }
            }

        });
    </script>
    <script>
        $('[data-toggle="tooltip"]').tooltip()
        //bank details

        $('.bank-id').change(function () {
            var id = $(this).val();
            $.ajax({
                url: "{{route('bankDetails')}}?val=" + id,
                type: "get",
                success: function (data) {
                    // console.log(data);
                    $('div.bank-details').html(data.data_genetare);
                    $('#r-side-img').hide();
                },
                error: function (jqXHR, textStatus, errorThrown) {

                }
            });
        });
    </script>

    <script>
        //change payment type

        $('#payment_type').change(function () {
            var id = $(this).val();
            var amount = $('input[name=coin]').val();
            var pay_type = document.querySelector('input[name="payment_type"]:checked').value;
            var payment_type = $('#payment_type').val();
            call_coin_rate(amount, pay_type, payment_type);

        });
    </script>

    <script>
        function call_coin_rate(amount, pay_type, payment_type) {
            // console.log(amount,pay_type,payment_type);
            $.ajax({
                type: "POST",
                url: "{{ route('buyCoinRate') }}",
                data: {
                    '_token': "{{ csrf_token() }}",
                    'amount': amount,
                    'payment_type': payment_type,
                    'pay_type': pay_type,
                },
                dataType: 'JSON',

                success: function (data) {
                    // console.log(data);
                    $('.coinAmount').text(data.amount);
                    $('.CoinInDoller').text(data.coin_price);
                    $('.totalBTC').text(data.btc_dlr);
                    $('#total_price').val(data.btc_dlr);
                    $('.coinType').text(data.coin_type);
                    if(data.no_phase == false) {
                      //  $('.coinFees').text(data.phase_fees);
                        $('.coinBonus').text(data.bonus);
                    }
                },
                error: function () {
                    $('.btc-price').addClass('d-none');
                    $('.private-sell-submit').attr('disabled', false);
                }
            });
        }
    </script>

    <script>
        function delay(callback, ms) {
            var timer = 0;
            return function () {
                var context = this, args = arguments;
                clearTimeout(timer);
                timer = setTimeout(function () {
                    callback.apply(context, args);
                }, ms || 0);
            };
        }

        function call_coin_payment() {
            var amount = $('input[name=coin]').val();
            var pay_type = document.querySelector('input[name="payment_type"]:checked').value;
            var payment_type = $('#payment_type').val();
            call_coin_rate(amount, pay_type, payment_type);
        }

        $("#amount").keyup(delay(function (e) {
            var amount = $('input[name=coin]').val();
            // if(document.querySelector('input[name="payment_type"]:checked') == null) {
            //     var pay_type = 4;
            // } else {
              //  var pay_type = document.querySelector('input[name="payment_type"]:checked').value;
            // }
           if (document.getElementById('payment_type').checked){
               var payment_type = $('#payment_type').val();
               call_coin_rate(amount, pay_type, payment_type);
           }


        }, 500));


    </script>

    <script>
        @if(isset($phase))
        function getTimeRemaining(endtime) {
            const total = Date.parse(endtime) - Date.parse(new Date());
            const seconds = Math.floor((total / 1000) % 60);
            const minutes = Math.floor((total / 1000 / 60) % 60);
            const hours = Math.floor((total / (1000 * 60 * 60)) % 24);
            const days = Math.floor(total / (1000 * 60 * 60 * 24));

            return {
                total,
                days,
                hours,
                minutes,
                seconds
            };
        }

        function initializeClock(id, endtime) {
            const clock = document.getElementById(id);
            const daysSpan = clock.querySelector('.days');
            const hoursSpan = clock.querySelector('.hours');
            const minutesSpan = clock.querySelector('.minutes');
            const secondsSpan = clock.querySelector('.seconds');

            function updateClock() {
                const t = getTimeRemaining(endtime);

                daysSpan.innerHTML = t.days;
                hoursSpan.innerHTML = ('0' + t.hours).slice(-2);
                minutesSpan.innerHTML = ('0' + t.minutes).slice(-2);
                secondsSpan.innerHTML = ('0' + t.seconds).slice(-2);

                if (t.total <= 0) {
                    clearInterval(timeinterval);
                }
            }

            updateClock();
            const timeinterval = setInterval(updateClock, 1000);
        }

        // const deadline = new Date(Date.parse(new Date()) + 15 * 24 * 60 * 60 * 1000);
        initializeClock('clockdiv', '{{$phase->end_date}}');
        @endif
        @if(isset($activePhase['futurePhase']) &&  ($activePhase['futurePhase']== true))
        function getTimeRemaining(endtime) {
            const total = Date.parse(endtime) - Date.parse(new Date());
            const seconds = Math.floor((total / 1000) % 60);
            const minutes = Math.floor((total / 1000 / 60) % 60);
            const hours = Math.floor((total / (1000 * 60 * 60)) % 24);
            const days = Math.floor(total / (1000 * 60 * 60 * 24));

            return {
                total,
                days,
                hours,
                minutes,
                seconds
            };
        }

        function initializeClock(id, endtime) {
            const clock = document.getElementById(id);
            const daysSpan = clock.querySelector('.days');
            const hoursSpan = clock.querySelector('.hours');
            const minutesSpan = clock.querySelector('.minutes');
            const secondsSpan = clock.querySelector('.seconds');

            function updateClock() {
                const t = getTimeRemaining(endtime);

                daysSpan.innerHTML = t.days;
                hoursSpan.innerHTML = ('0' + t.hours).slice(-2);
                minutesSpan.innerHTML = ('0' + t.minutes).slice(-2);
                secondsSpan.innerHTML = ('0' + t.seconds).slice(-2);

                if (t.total <= 0) {
                    clearInterval(timeinterval);
                }
            }

            updateClock();
            const timeinterval = setInterval(updateClock, 1000);
        }

        // const deadline = new Date(Date.parse(new Date()) + 15 * 24 * 60 * 60 * 1000);
        initializeClock('futurePhase', '{{$activePhase['futureDate']}}');
        @endif
    </script>


@endsection
