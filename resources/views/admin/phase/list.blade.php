@extends('admin.master',['menu'=>$menu, 'sub_menu'=>$sub_menu])
@section('title', isset($title) ? $title : '')
@section('style')
@endsection
@section('content')
    <!-- breadcrumb -->
    <div class="custom-breadcrumb">
        <div class="row">
            <div class="col-9">
                <ul>
                    <li>{{__('Ico Phase Management')}}</li>
                    <li class="active-item">{{ $title }}</li>
                </ul>
            </div>
            <div class="col-sm-3 text-right">
                <a class="add-btn theme-btn" href="{{route('adminPhaseAdd')}}">{{__('Create Phase')}}</a>
            </div>
        </div>
    </div>
    <!-- /breadcrumb -->

    <!-- User Management -->
    <div class="user-management">
        <div class="row">
            <div class="col-12">
                <div class="card-body">
                    <div class="header-bar p-4">
                        <div class="table-title">
                            <h3>{{__('Phase Management')}}</h3>
                        </div>
                    </div>

                    <div class="phase-body">
                        <div class="row">
                            @if(isset($phases[0]))
                                @foreach($phases as $phase)
                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                        <div class="single-phase">
                                            <div class="card">
                                                <div class="card-header">

                                                    <div class="top">
                                                        <div class="left">
                                                            <p>
                                                                {{__('stage ')}}
                                                                @if((strtotime(\Carbon\Carbon::parse($phase->end_date)->format('Y-m-d H:i:s')) >= strtotime(\Carbon\Carbon::now()->format('Y-m-d H:i:s'))) && ($phase->status == STATUS_SUCCESS))
                                                                    <span
                                                                        class="badge badge-success">{{__('Running')}}</span>
                                                                @elseif((strtotime(\Carbon\Carbon::parse($phase->end_date)->format('Y-m-d H:i:s')) < strtotime(\Carbon\Carbon::now()->format('Y-m-d H:i:s'))) && ($phase->status == STATUS_SUCCESS))
                                                                    <span style="background-color: #ff9121 !important;"
                                                                          class="badge badge-warning">{{__('Expired')}}</span>
                                                                @else
                                                                    <span style="background-color: red !important;"
                                                                          class="badge badge-danger">{{__('Inactive')}}</span>
                                                                @endif
                                                            </p>
                                                        </div>
                                                        <div class="right">
                                                            <div class="btn-group">
                                                                <button type="button" class="btn dropdown-toggle"
                                                                        data-toggle="dropdown" aria-haspopup="true"
                                                                        aria-expanded="false">
                                                                    <i class="fa fa-ellipsis-h "></i>
                                                                </button>
                                                                <div class="dropdown-menu dropdown-menu-right">
                                                                    <a href="{{route('phaseStatusChange',encrypt($phase->id))}}">
                                                                        <button class="dropdown-item" type="button">
                                                                            @if($phase->status == STATUS_SUCCESS) {{__('Inactive')}} @else {{__('Active')}} @endif
                                                                        </button>
                                                                    </a>
                                                                    <a href="{{route('phaseEdit',encrypt($phase->id))}}">
                                                                        <button class="dropdown-item"
                                                                                type="button">{{__('Update')}}</button>
                                                                    </a>
                                                                    <a href="{{route('phaseDelete',encrypt($phase->id))}}">
                                                                        <button class="dropdown-item"
                                                                                type="button">{{__('Delete')}}</button>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="bottom">
                                                        <div class="left">
                                                            <h4>{{$phase->phase_name}}</h4>
                                                            <p class="ico-issued">{{__('ico issued')}}</p>
                                                            <p>{{$phase->amount}}</p>
                                                        </div>
                                                        <div class="right">
                                                            @php
                                                                $total_sell = \App\Model\BuyCoinHistory::where('status',STATUS_SUCCESS)->where('phase_id',$phase->id)->sum('coin');
                                                                $progress_bar = 0;


                                                              $targate = $phase->amount;
                                                              $unsold = ($targate >=  $total_sell ) ? bcsub($targate,$total_sell,8) : 0;
                                                              if ($targate != 0){
                                                                  $sale = bcmul(100, $total_sell,8);
                                                                  $progress_bar = ceil(($sale/$targate));
                                                              }
                                                            @endphp
{{--                                                            <div class="progress-box">--}}
{{--                                                                <div class="progress-percent">--}}
{{--                                                                    <svg>--}}
{{--                                                                        <circle cx="45" cy="45" r="45"></circle>--}}
{{--                                                                        <circle cx="45" cy="45" r="45" style="stroke-dashoffset: calc(285 - (285 * {{$progress_bar}}) / 100);"></circle>--}}
{{--                                                                    </svg>--}}
{{--                                                                    <div class="number">--}}
{{--                                                                        <h3>{{ $progress_bar }} <span>%</span></h3>--}}
{{--                                                                        <p>sold</p>--}}
{{--                                                                    </div>--}}
{{--                                                                </div>--}}
{{--                                                            </div>--}}

                                                            <div class="progress-wrapper">
                                                                <svg class="progress blue noselect" data-progress="{{ $progress_bar }}" viewBox="0 0 80 80" x="0px" y="0px">
                                                                    <path class="track" d="M5,40a35,35 0 1,0 70,0a35,35 0 1,0 -70,0"/>
                                                                    <path class="fill" d="M5,40a35,35 0 1,0 70,0a35,35 0 1,0 -70,0"/>
                                                                    <text class="value" x="50%" y="55%">0%</text>
                                                                </svg>
                                                                <p class="sold">sold</p>
                                                            </div><!-- /progress circle -->
                                                            <p>{{__('sold')}} {{ $total_sell }} {{ allsetting('coin_name')}}</p>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="card-body">

                                                    <div class="base-price">
                                                        <h4>{{__('base price')}}</h4>
                                                        <p>{{$phase->rate}} <sub>{{ __('USD')}}</sub></p>
                                                    </div>
                                                    <div class="base-price">
{{--                                                        <h4>{{__('fees')}}</h4>--}}
{{--                                                        <p>{{number_format($phase->fees,4)}} <sub>%</sub></p>--}}
                                                        <!-- <ul>
                                                            <li><span class="coin">btc</span><span
                                                                    class="price">0.00000000 <sub>btc</sub></span></li>
                                                        </ul> -->
                                                    </div>
                                                    <div class="base-price">
                                                        <h4>{{__('bonus')}}</h4>
                                                        <p>{{number_format($phase->bonus,4)}} <sub>%</sub></p>
                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    <div class="start-date">
                                                        <h6>{{__('start date')}}</h6>
                                                        <p>{{ date('d M y', strtotime($phase->start_date))}}</p>
                                                    </div>
                                                    <div class="end-date">
                                                        <h6>{{__('end date')}}</h6>
                                                        <p>{{ date('d M y', strtotime($phase->end_date))}}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- col-4 -->
                                @endforeach
                            @else
                                <div class="col-md-12">
                                    <p class="text-center text-danger">{{__('No phase found')}}</p>
                                </div>
                            @endif

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- /User Management -->

@endsection

@section('script')
    <script>
    var forEach = function (array, callback, scope) {
        for (var i = 0; i < array.length; i++) {
            callback.call(scope, i, array[i]);
        }
    };
    window.onload = function () {
        var max = -219.99078369140625;
        forEach(document.querySelectorAll(".progress"), function (index, value) {
            percent = value.getAttribute("data-progress");
            value
                .querySelector(".fill")
                .setAttribute(
                    "style",
                    "stroke-dashoffset: " + ((100 - percent) / 100) * max
                );
            value.querySelector(".value").innerHTML = percent + "%";
        });
    };
</script>
@endsection
