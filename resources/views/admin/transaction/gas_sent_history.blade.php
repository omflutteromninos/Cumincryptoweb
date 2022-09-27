@extends('admin.master',['menu'=>'transaction', 'sub_menu'=>'gas_sent'])
@section('title', isset($title) ? $title : '')
@section('style')
@endsection
@section('content')
    <!-- breadcrumb -->
    <div class="custom-breadcrumb">
        <div class="row">
            <div class="col-9">
                <ul>
                    <li>{{__('Transaction History')}}</li>
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
                <div class="card-body">
                    <div class="table-area">
                        <div>
                            <table id="table" class="table table-borderless custom-table display text-center" width="100%">
                                <thead>
                                <tr>
                                    <th scope="col">{{__('Deposit Id')}}</th>
                                    <th scope="col">{{__('Amount')}}</th>
                                    <th scope="col">{{__('Base Coin Type')}}</th>
                                    <th scope="col">{{__('From Address')}}</th>
                                    <th scope="col">{{__('To Address')}}</th>
                                    <th scope="col">{{__('Tx Hash')}}</th>
                                    <th scope="col">{{__('Status')}}</th>
                                    <th scope="col">{{__('Created At')}}</th>
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
    <!-- /User Management -->

@endsection

@section('script')
    <script>
        (function($) {
            "use strict";

            $('#table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 10,
                retrieve: true,
                bLengthChange: true,
                responsive: true,
                ajax: '{{route('adminGasSendHistory')}}',
                order: [7, 'desc'],
                autoWidth: false,
                language: {
                    paginate: {
                        next: 'Next &#8250;',
                        previous: '&#8249; Previous'
                    }
                },
                columns: [
                    {"data": "deposit_id", "orderable": true},
                    {"data": "amount", "orderable": true},
                    {"data": "coin_type", "orderable": true},
                    {"data": "admin_address", "orderable": true},
                    {"data": "user_address", "orderable": true},
                    {"data": "transaction_hash", "orderable": false},
                    {"data": "status", "orderable": false},
                    {"data": "created_at", "orderable": true},
                ],
            });
        })(jQuery);
    </script>
@endsection
