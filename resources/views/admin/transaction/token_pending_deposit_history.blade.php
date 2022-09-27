@extends('admin.master',['menu'=>'transaction', 'sub_menu'=>'pending_deposit'])
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
                        <h3>{{ __('If user deposit to your system but admin did not receive the token then you can adjust the token mismatch from here. ') }}</h3>
                        <p class="text-danger">{{__('Note')}}: {{__('Please analysis everything then do it, otherwise you will loss token, so be careful')}}</p>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-area">
                        <div>
                            <table id="table" class="table table-borderless custom-table display text-center" width="100%">
                                <thead>
                                <tr>
                                    <th scope="col">{{__('Amount')}}</th>
                                    <th scope="col">{{__('From Address')}}</th>
                                    <th scope="col">{{__('To Address')}}</th>
                                    <th scope="col">{{__('Tx Hash')}}</th>
                                    <th scope="col">{{__('Status')}}</th>
                                    <th scope="col">{{__('Created At')}}</th>
                                    <th class="all" scope="col">{{__('Actions')}}</th>
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
                ajax: '{{route('adminPendingDepositHistory')}}',
                order: [5, 'asc'],
                autoWidth: false,
                language: {
                    paginate: {
                        next: 'Next &#8250;',
                        previous: '&#8249; Previous'
                    }
                },
                columns: [
                    {"data": "amount", "orderable": true},
                    {"data": "from_address", "orderable": true},
                    {"data": "address", "orderable": true},
                    {"data": "transaction_id", "orderable": false},
                    {"data": "status", "orderable": false},
                    {"data": "created_at", "orderable": true},
                    {"data": "actions", "orderable": false},
                ],
            });
        })(jQuery);
    </script>
@endsection
