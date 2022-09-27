@extends('user.master',['menu'=>'coin_request','sub_menu'=>'pending_request'])
@section('title', isset($title) ? $title : '')
@section('style')
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12 mb-xl-0 mb-4">
            <div class="card cp-user-custom-card">
                <div class="card-body">
                    <div class="cp-user-card-header-area">
                        <h4>{{__('Pending Coin Request')}}</h4>
                    </div>
                    <div class="cp-user-buy-coin-content-area">
                        <div class="cp-user-wallet-table table-responsive">
                            <table id="table" class="table">
                                <thead>
                                <tr>
                                    <th>{{__('Requested User')}}</th>
                                    <th>{{__('Coin Amount')}}</th>
                                    <th>{{__('Coin Name')}}</th>
                                    <th>{{__('Created At')}}</th>
                                    <th>{{__('Actions')}}</th>
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
            ajax: '{{route('pendingRequest')}}',
            order: [3, 'desc'],
            autoWidth: false,
            language: {
                paginate: {
                    next: 'Next &#8250;',
                    previous: '&#8249; Previous'
                }
            },
            columns: [
                {"data": "receiver_user_id","orderable": false},
                {"data": "amount","orderable": false},
                {"data": "coin_type","orderable": false},
                {"data": "created_at","orderable": false},
                {"data": "action","orderable": false},
            ],
        });
    </script>
@endsection
