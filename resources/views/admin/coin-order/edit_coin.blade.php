@extends('admin.master',['menu'=>'coin'])
@section('title', isset($title) ? $title : '')
@section('style')
@endsection
@section('content')
    <!-- breadcrumb -->
    <div class="custom-breadcrumb">
        <div class="row">
            <div class="col-9">
                <ul>
                    <li>{{__('Coin')}}</li>
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
                <div class="header-bar p-4">
                    <div class="table-title">
                        <h3>{{ $title }}</h3>
                    </div>
                </div>
                <div class="profile-info-form">
                    <div class="card-body">
                        {{Form::open(['route'=>'adminCoinSaveProcess', 'files' => true])}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="controls">
                                        <div class="form-label">{{__('Coin Full Name')}}</div>
                                        <input type="text" class="form-control" name="name" @if(isset($item))value="{{$item->name}}" @else value="{{old('name')}}" @endif>
                                        <pre class="text-danger">{{$errors->first('name')}}</pre>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="controls">
                                        <div class="form-label">{{__('Withdrawal fees (%)')}}</div>
                                        <input type="text" class="form-control" name="withdrawal_fees" @if(isset($item))value="{{$item->withdrawal_fees}}" @else value="{{old('withdrawal_fees')}}" @endif>
                                        <pre class="text-danger">{{$errors->first('withdrawal_fees')}}</pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="controls">
                                        <div class="form-label">{{__('Minimum Withdrawal')}}</div>
                                        <input type="text" class="form-control" name="minimum_withdrawal"
                                               @if(isset($item))value="{{$item->minimum_withdrawal}}" @else value="0.00000001" @endif >
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="controls">
                                        <div class="form-label">{{__('Maximum Withdrawal')}}</div>
                                        <input type="text" class="form-control" name="maximum_withdrawal"
                                               @if(isset($item))value="{{$item->maximum_withdrawal}}" @else value="99999999" @endif>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="controls">
                                        <div class="form-label">{{__('Withdrawal Status')}}</div>
                                        <label class="switch">
                                            <input type="checkbox" name="is_withdrawal" @if(isset($item) && $item->is_withdrawal==1)checked  @endif>
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @if(isset($item) && ($item->type != DEFAULT_COIN_TYPE))
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="controls">
                                            <div class="form-label">{{__('Active Status')}}</div>
                                            <label class="switch">
                                                <input type="checkbox" name="status" @if(isset($item) && $item->status==1)checked  @endif>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-label">{{__('Coin Icon')}}</div>
                                <div class="form-group  ">
                                    <div id="file-upload" class="section-p">
                                        <input type="file" placeholder="0.00" name="coin_icon" value=""
                                               id="file" ref="file" class="dropify"
                                               @if(isset($item->coin_icon) && (!empty($item->coin_icon)))  data-default-file="{{show_image_path($item->coin_icon,'coin/')}}" @endif />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                @if(isset($item))<input type="hidden" name="coin_id" value="{{encrypt($item->id)}}">  @endif
                                <button type="submit" class="btn btn-success">{{$button_title}}</button>
                            </div>
                        </div>
                        {{Form::close()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /User Management -->

@endsection

@section('script')
    <script>
        function processForm(active_id) {
            $.ajax({
                type: "POST",
                url: "{{ route('adminCoinStatus') }}",
                data: {
                    '_token': "{{ csrf_token() }}",
                    'active_id': active_id
                },
                success: function (data) {
                    console.log(data);
                }
            });
        }

    </script>
@endsection
