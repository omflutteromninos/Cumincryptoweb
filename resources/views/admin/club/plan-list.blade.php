@extends('admin.master',['menu'=>'club', 'sub_menu'=>'plan_list'])
@section('title', isset($title) ? $title : '')
@section('style')
@endsection
@section('content')
    <!-- breadcrumb -->
    <!-- breadcrumb -->
    <div class="custom-breadcrumb">
        <div class="row">
            <div class="col-12">
                <ul>
                    <li>{{__('Membership Club')}}</li>
                    <li class="active-item">{{__('Plan List')}}</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /breadcrumb -->

    <!-- User Management -->
    <div class="user-management padding-30">
        <div class="row">
            <div class="col-12">
                <div class="header-bar">
                    <div class="table-title">
                        <h3>{{__('Plan List')}}</h3>
                    </div>
                    <div class="right d-flex align-items-center">
                        <div class="add-btn">
                            <a href="{{route('planAdd')}}">{{__('+ Add')}}</a>
                        </div>
                    </div>
                </div>
                <div class="table-area">
                    <div class="table-responsive">
                        <table id="table" class="table table-borderless custom-table display text-center">
                            <thead>
                            <tr>
                                <th>{{__('Plan Name')}}</th>
                                <th>{{__('Minimum Amount')}}</th>
                                <th>{{__('Duration')}}</th>
                                <th>{{__('Bonus Type')}}</th>
                                <th>{{__('Bonus')}}</th>
                                <th>{{__('Bonus Coin Type')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Created At')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
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
        $('#table').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 10,
            retrieve: true,
            bLengthChange: true,
            responsive: false,
            ajax: '{{route('planList')}}',
            order: [7, 'desc'],
            autoWidth: false,
            language: {
                paginate: {
                    next: 'Next &#8250;',
                    previous: '&#8249; Previous'
                }
            },
            columns: [
                {"data": "plan_name","orderable": false},
                {"data": "amount","orderable": false},
                {"data": "duration","orderable": false},
                {"data": "bonus_type","orderable": false},
                {"data": "bonus","orderable": false},
                {"data": "bonus_coin_type","orderable": false},
                {"data": "status","orderable": false},
                {"data": "created_at","orderable": false},
                {"data": "actions","orderable": false}
            ],
        });

    </script>
@endsection
