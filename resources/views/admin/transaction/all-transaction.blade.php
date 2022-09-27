@extends('admin.master',['menu'=>'transaction', 'sub_menu'=>'transaction_all'])
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
                    <li class="active-item">{{__('All Deposit And Withdrawal History')}}</li>
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
                    <li>
                        <a class=" active  nav-link " data-id="profile" data-toggle="pill" role="tab" data-controls="profile" aria-selected="true" href="#profile">
                            <img src="{{asset('assets/admin/images/sidebar-icons/wallet.svg')}}" class="img-fluid" alt="">
                            <span>{{__('Deposit History')}}</span>
                        </a>
                    </li>
                    <li>
                        <a class=" @if(isset($tab) && $tab=='edit_profile') active @endif nav-link  " data-id="edit_profile" data-toggle="pill" role="tab" data-controls="edit_profile" aria-selected="true" href="#edit_profile">
                            <img src="{{asset('assets/admin/images/sidebar-icons/coin.svg')}}" class="img-fluid" alt="">
                            <span>{{__('Withdrawal History')}}</span>
                        </a>
                    </li>
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
                                            <th class="all">{{__('Type')}}</th>
                                            <th class="all">{{__('Sender')}}</th>
                                            <th class="all">{{__('Coin Type')}}</th>
                                            <th class="desktop">{{__('Address')}}</th>
                                            <th class="desktop">{{__('Receiver')}}</th>
                                            <th class="desktop">{{__('Amount')}}</th>
                                            <th class="desktop">{{__('Fees')}}</th>
                                            <th class="desktop">{{__('Transaction Id')}}</th>
                                            <th class="desktop">{{__('Status')}}</th>
                                            <th class="all">{{__('Created Date')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                        <div class="tab-pane fade @if(isset($tab) && $tab=='edit_profile')show active @endif" id="edit_profile" role="tabpanel" aria-labelledby="apisetting-tab">
                            <div class="table-area">
                                <div class="table-responsive">
                                    <table id="withdrawal_table" class="table table-borderless custom-table display text-center"
                                           width="100%">
                                        <thead>
                                        <tr>
                                            <th class="all">{{__('Type')}}</th>
                                            <th class="all">{{__('Sender')}}</th>
                                            <th class="all">{{__('Coin type')}}</th>
                                            <th class="desktop">{{__('Address')}}</th>
                                            <th class="desktop">{{__('Receiver')}}</th>
                                            <th class="desktop">{{__('Amount')}}</th>
                                            <th class="desktop">{{__('Fees')}}</th>
                                            <th class="desktop">{{__('Transaction Id')}}</th>
                                            <th class="desktop">{{__('Status')}}</th>
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
            ajax: '{{route('adminTransactionHistory')}}',
            order: [9, 'desc'],
            autoWidth: false,
            language: {
                paginate: {
                    next: 'Next &#8250;',
                    previous: '&#8249; Previous'
                }
            },
            columns: [
                {"data": "address_type"},
                {"data": "sender"},
                {"data": "type"},
                {"data": "address"},
                {"data": "receiver"},
                {"data": "amount"},
                {"data": "fees"},
                {"data": "transaction_id"},
                {"data": "status"},
                {"data": "created_at"}
            ]
        });

        $('#withdrawal_table').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 25,
            responsive: true,
            ajax: '{{route('adminWithdrawalHistory')}}',
            order:[9,'desc'],
            autoWidth:false,
            language: {
                paginate: {
                    next: 'Next &#8250;',
                    previous: '&#8249; Previous'
                }
            },
            columns: [
                {"data": "address_type"},
                {"data": "sender"},
                {"data": "coin_type"},
                {"data": "address"},
                {"data": "receiver"},
                {"data": "amount"},
                {"data": "fees"},
                {"data": "transaction_hash"},
                {"data": "status"},
                {"data": "created_at"}
            ]
        });
    </script>
@endsection
