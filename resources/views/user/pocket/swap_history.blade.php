@extends('user.master',['menu'=>'pocket','sub_menu'=>'swap_history'])
@section('title', isset($title) ? $title : __('Convert History'))
@section('style')
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12 mb-xl-0 mb-4">
            <div class="card cp-user-custom-card">
                <div class="card-body">
                    <div class="cp-user-card-header-area">
                        <h4>{{__('Coin Swap History')}}</h4>
                    </div>
                    <div class="cp-user-buy-coin-content-area">
                        <div class="cp-user-wallet-table table-responsive">
                            <table id="table" class="table">
                                <thead>
                                <tr>
                                    <th>{{__('From Wallet')}}</th>
                                    <th>{{__('To Wallet')}}</th>
                                    <th>{{__('Requested Amount')}}</th>
                                    <th>{{__('Converted Amount')}}</th>
                                    <th>{{__('Rate')}}</th>
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
            ajax: '{{route('coinSwapHistory')}}',
            order: [5, 'desc'],
            autoWidth: false,
            language: {
                paginate: {
                    next: 'Next &#8250;',
                    previous: '&#8249; Previous'
                }
            },
            columns: [
                {"data": "from_wallet_id","orderable": false},
                {"data": "to_wallet_id","orderable": false},
                {"data": "requested_amount","orderable": false},
                {"data": "converted_amount","orderable": false},
                {"data": "rate","orderable": false},
                {"data": "created_at","orderable": false},
            ],
        });
    </script>
@endsection
