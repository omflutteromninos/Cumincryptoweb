@extends('admin.master',['menu'=>'transaction', 'sub_menu'=>'transaction_default'])
@section('title', isset($title) ? $title : '')
@section('style')
@endsection
@section('content')
    <!-- breadcrumb -->
    <div class="custom-breadcrumb">
        <div class="row">
            <div class="col-12">
                <ul>
                    <li>{{__('Transaction')}}</li>
                    <li class="active-item">{{__('Default Coin Send or Receive History')}}</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /breadcrumb -->

    <!-- User Management -->
    <div class="user-management">
        <div class="row">
            <div class="col-12">
                <ul class="nav user-management-nav profile-nav mb-3" id="pills-tab" role="tablist">

                </ul>
                <div class="card-body">
                    <div class="tab-content tab-pt-n" id="tabContent">
                        <div class="tab-pane fade show active " id="profile" role="tabpanel" aria-labelledby="general-setting-tab">
                            <div class="table-area">
                                <div class="table-responsive">
                                    <table id="deposit_table" class="table table-borderless custom-table display text-center"
                                           width="100%">
                                        <thead>
                                        <tr>
                                            <th class="all">{{__('Sender')}}</th>
                                            <th class="all">{{__('Receiver')}}</th>
                                            <th class="all">{{__('Amount')}}</th>
                                            <th class="all">{{__('Status')}}</th>
                                            <th class="all">{{__('Created Date')}}</th>
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
            </div>
        </div>
    </div>
    <!-- /User Management -->
@endsection

@section('script')
    <script>
        $('#deposit_table').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 25,
            responsive: true,
            ajax: '{{route('adminDefaultCoinTransactionHistory')}}',
            order: [4, 'desc'],
            autoWidth: false,
            language: {
                paginate: {
                    next: 'Next &#8250;',
                    previous: '&#8249; Previous'
                }
            },
            columns: [
                {"data": "sender_user_id"},
                {"data": "receiver_user_id"},
                {"data": "amount"},
                {"data": "status"},
                {"data": "created_at"}
            ]
        });

    </script>
@endsection
