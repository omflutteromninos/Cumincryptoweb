@extends('admin.master',['menu'=>$menu, 'sub_menu'=>$sub_menu])
@section('title', isset($title) ? $title : '')
@section('style')
@endsection
@section('content')
    <!-- breadcrumb -->
    <div class="custom-breadcrumb">
        <div class="row">
            <div class="col-12">
                <ul>
                    <li>{{__('Ico Phase Management')}}</li>
                    <li class="active-item">{{ $title }}</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /breadcrumb -->

    <!-- User Management -->
    <div class="user-management">
        <div class="row">
            <div class="col-12">
                <div class="profile-info-form ico-phase">
                    <div class="card-body">
                        <form action="{{route('adminPhaseAddProcess')}}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mt-20">
                                    <div class="form-group">
                                        <label for="firstname">{{__('Phase Name')}}</label>
                                        <input type="text" name="phase_name" class="form-control" id="firstname" placeholder="{{__('Phase name')}}"
                                               @if(isset($item)) value="{{$item->phase_name}}" @else value="{{old('phase_name')}}" @endif>
                                        <span class="text-danger"><strong>{{ $errors->first('phase_name') }}</strong></span>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-20">
                                    <div class="form-group">
                                        <label for="">{{__('Amount')}}</label>
                                        <input type="text" name="amount" class="form-control" id="" placeholder="{{__('Target Amount')}}"
                                               @if(isset($item)) value="{{$item->amount}}" @else value="{{old('amount')}}" @endif>
                                        <span class="text-danger"><strong>{{ $errors->first('amount') }}</strong></span>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-20">
                                    <div class="form-group">
                                        <label for="">{{__('Start Date')}}</label>
                                        <div class="input-group date" data-provide="datepicker">
                                            <input type="text" class="form-control datepicker" name="start_date"
                                                   @if(isset($item)) value="{{$item->start_date}}" @else value="{{old('start_date')}}" @endif>
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                        <span class="text-danger"><strong>{{ $errors->first('start_date') }}</strong></span>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-20">
                                    <div class="form-group">
                                        <label for="">{{__('End Date')}}</label>
                                        <div class="input-group date" data-provide="datepicker">
                                            <input type="text" class="form-control datepicker" name="end_date"
                                                   @if(isset($item)) value="{{$item->end_date}}" @else value="{{old('end_date')}}" @endif>
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                        <span class="text-danger"><strong>{{ $errors->first('end_date') }}</strong></span>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-20">
                                    <div class="form-group">
                                        <label for="">{{__('Phase Rate (in usd)')}}</label>
                                        <input type="text" name="rate" class="form-control" id="" placeholder="{{__('1 coin = ? usd')}}"
                                               @if(isset($item)) value="{{$item->rate}}" @else value="{{old('rate')}}" @endif>
                                        <span class="text-danger"><strong>{{ $errors->first('rate') }}</strong></span>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-20">
                                    <div class="form-group">
                                        <label for="">{{__('Affiliation Level')}}</label>
                                        <input type="text" name="affiliation_level" class="form-control" id="" placeholder="{{__('Max 3 level')}}"
                                               @if(isset($item)) value="{{$item->affiliation_level}}" @else value="{{old('affiliation_level')}}" @endif>
                                        <span class="text-danger"><strong>{{ $errors->first('affiliation_level') }}</strong></span>
                                    </div>
                                </div>

                                <!-- <div class="col-md-6 mt-20">
                                    <div class="form-group">
                                        <label for="">{{__('Affiliation Percentage')}}</label>
                                        <input type="text" name="affiliation_percentage" class="form-control" id="" placeholder="{{__('Affiliation level percentage')}}"
                                               @if(isset($item)) value="{{$item->affiliation_percentage}}" @else value="{{old('affiliation_percentage')}}" @endif>
                                        <span class="text-danger"><strong>{{ $errors->first('affiliation_percentage') }}</strong></span>
                                    </div>
                                </div> -->
{{--                                <div class="col-md-6 mt-20">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label for="">{{__('System Fees percentage')}}</label>--}}
{{--                                        <input type="text" name="fees" class="form-control" id="" placeholder="{{__('Fees percentage')}}"--}}
{{--                                               @if(isset($item)) value="{{$item->fees}}" @else value="{{old('fees')}}" @endif>--}}
{{--                                        <span class="text-danger"><strong>{{ $errors->first('fees') }}</strong></span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="col-md-6 mt-20">
                                    <div class="form-group">
                                        <label for="">{{__('Bonus percentage')}}</label>
                                        <input type="text" name="bonus" class="form-control" id="" placeholder="{{__('Bonus percentage')}}"
                                               @if(isset($item)) value="{{$item->bonus}}" @else value="{{old('bonus')}}" @endif>
                                        <span class="text-danger"><strong>{{ $errors->first('bonus') }}</strong></span>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-20">
                                    <div class="form-group">
                                        <label>{{__('Activation Status')}}</label>
                                        <div class="cp-select-area">
                                            <select name="status" class="form-control wide" >
                                                @foreach(status() as $key => $value)
                                                    <option @if(isset($item) && ($item->status == $key)) selected
                                                            @elseif((old('status') != null) && (old('status') == $key)) @endif value="{{ $key }}">{{$value}}</option>
                                                    <span class="text-danger"><strong>{{ $errors->first('status') }}</strong></span>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    @if(isset($item))
                                        <input type="hidden" name="edit_id" value="{{encrypt($item->id)}}">
                                    @endif
                                    <button class="button-primary theme-btn">@if(isset($item)) {{__('Update')}} @else {{__('Save')}} @endif</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /User Management -->

@endsection

@section('script')
    <script>
        $('.datepicker').datepicker();

    </script>
@endsection
