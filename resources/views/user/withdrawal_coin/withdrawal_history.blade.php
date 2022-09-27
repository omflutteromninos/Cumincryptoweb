@extends('user.master',['menu'=>'coin_request','sub_menu'=>'default_withdrawal_coin'])
@section('title', isset($title) ? $title : '')
@section('style')
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12 mb-xl-0 mb-4">
            <div class="card cp-user-custom-card">
                <div class="card-body">
                    <div class="cp-user-card-header-area">
                        <h4>{{__('Withdrawal Coin History')}}</h4>
                    </div>
                    <div class="cp-user-buy-coin-content-area">
                        <div class="cp-user-wallet-table table-responsive">
                            <table id="table" class="table">
                                <thead>
                                <tr>
                                    <th>{{__('Receiver')}}</th>
                                    <th>{{__('Coin Amount')}}</th>
                                    <th>{{__('Transaction Hash')}}</th>
                                    <th>{{__('Status')}}</th>
                                    <th>{{__('Created At')}}</th>
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

@endsection

@section('script')
    <script>
        $('#table').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 10,
            retrieve: true,
            bLengthChange: true,
            responsive: true,
            ajax: '{{route('defaultWithdrawalHistory')}}',
            order: [4, 'desc'],
            autoWidth: false,
            language: {
                paginate: {
                    next: 'Next &#8250;',
                    previous: '&#8249; Previous'
                }
            },
            columns: [
                {"data": "address","orderable": true},
                {"data": "amount","orderable": true},
                {"data": "transaction_hash","orderable": true},
                {"data": "status","orderable": false},
                {"data": "created_at"},
            ],
        });
    </script>
@endsection
