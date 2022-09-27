@extends('user.master',['menu'=>'pocket','sub_menu'=>'my_pocket'])
@section('title', isset($title) ? $title : '')
@section('style')
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card cp-user-custom-card cp-user-wallet-card">
                <div class="card-body">
                    <div class="cp-user-card-header-area">
                        <div class="cp-user-title">
                            <h4>{{__('Withdrawal Approvals for ')}}({{$tempWithdraw->amount}} {{$tempWithdraw->wallet->coin_type}} to {{$tempWithdraw->address}})</h4>
                            <p style="color: #B4B8D7"><b>{{__('Need')}} {{$total_required_approval - $approved_count}} {{('more approval')}}</b></p>
                        </div>
                    </div>

                    <div class="cp-user-wallet-table table-responsive">
                        <table class="table table-borderless cp-user-custom-table" width="100%">
                            <thead>
                            <tr>
                                <th class="all">{{__('Name')}}</th>
                                <th class="all">{{__('Email')}}</th>
                                <th class="all">{{__('Phone')}}</th>
                                <th class="all">{{__('Approved?')}}</th>
                                <th class="all">{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($co_users[0]))
                                @foreach($co_users as $co_user)
                                    <tr>
                                        <td>{{ $co_user->user->first_name }} {{ $co_user->user->last_name }}
                                            @if($tempWithdraw->user_id == $co_user->user->id)
                                                <span class="badge badge-pill badge-warning">{{__('Withdraw Requested')}}</span>
                                            @endif
                                        </td>
                                        <td>{{ $co_user->user->email }}</td>
                                        <td>{{ $co_user->user->phone }}</td>
                                        <td>
                                            @if($co_user->approved == STATUS_ACCEPTED)
                                                <span class="badge badge-pill badge-success">{{__('Yes')}}</span>
                                            @elseif($co_user->approved == STATUS_PENDING)
                                                <span class="badge badge-pill badge-warning">{{__('No')}}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <ul class="d-flex justify-content-center align-items-center">
                                                @if($co_user->user_id == \Illuminate\Support\Facades\Auth::id() && $co_user->approved == STATUS_PENDING)
                                                <li>
                                                    <a title="{{__('Approve withdraw')}}" class="confirm-modal" data-title="{{__('Do you really want to approve?')}}"
                                                       href="javascript:" data-href="{{route('approveCoWalletWithdraw', $tempWithdraw->id)}}">
                                                        <img
                                                            src="{{asset('assets/user/images/wallet-table-icons/send.svg')}}"
                                                            class="img-fluid" alt="">
                                                    </a>
                                                </li>
                                                @else
                                                    N/A
                                                @endif
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

@endsection

@section('script')

@endsection
