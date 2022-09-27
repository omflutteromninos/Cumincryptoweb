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
                    <div class="right d-flex align-items-center">
                        <div class="add-btn-new mb-2">
                            <a class="add-btn theme-btn" href="{{route('adminCoinListWithCoinPayment',['update'=> 'coinPayment'])}}">{{__('Adjust Coin With CoinPayment')}}</a>
                        </div>
                    </div>
                </div>
                <div class="table-area">
                    <div class="table-responsive">
                        <table id="table" class=" table table-borderless custom-table display text-center" width="100%">
                            <thead>
                            <tr>
                                <th scope="col">{{__('Coin Name')}}</th>
                                <th scope="col">{{__('Coin Type')}}</th>
                                <th scope="col">{{__('Minimum Withdraw Amount')}}</th>
                                <th scope="col">{{__('Maximum Withdraw Amount')}}</th>
                                <th scope="col">{{__('Fess(%)')}}</th>
                                <th scope="col">{{__('Status')}}</th>
                                <th scope="col">{{__('Updated At')}}</th>
                                <th scope="col">{{__('Actions')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($coins))
                                @foreach($coins as $coin)
                                    <tr>
                                        <td> {{$coin->name}} </td>
                                        <td> {{find_coin_type($coin->type)}} </td>
                                        <td> {{$coin->minimum_withdrawal}} </td>
                                        <td> {{$coin->maximum_withdrawal}} </td>
                                        <td> {{$coin->withdrawal_fees}} </td>
                                        <td>
                                            @if($coin->type == DEFAULT_COIN_TYPE)
                                                {{__("N/A")}}
                                            @else
                                            <div>
                                                <label class="switch">
                                                    <input type="checkbox" onclick="return processForm('{{$coin->id}}')"
                                                           id="notification" name="security" @if($coin->status == STATUS_ACTIVE) checked @endif>
                                                    <span class="slider" for="status"></span>
                                                </label>
                                            </div>
                                            @endif
                                        </td>
                                        <td> {{$coin->updated_at}}</td>
                                        <td>
                                            <ul class="d-flex activity-menu"><li class="viewuser">
                                                    <a title="{{__('Update')}}" href="{{route('adminCoinEdit', encrypt($coin->id))}}"><i class="fa fa-pencil"></i></a></li>
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
    </div>
    <!-- /User Management -->

@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('.custom-table').DataTable({
                responsive: true,
                paging: true,
                searching: true,
                ordering:  true,
                select: false,
                bDestroy: true
            });


        });
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
