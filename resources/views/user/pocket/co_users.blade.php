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
                            <h4>{{__('Co Users Of Pocket ')}}({{$wallet->name}})</h4>
                        </div>
                    </div>

                    <div class="cp-user-wallet-table table-responsive">
                        <table class="table table-borderless cp-user-custom-table" width="100%">
                            <thead>
                            <tr>
                                <th class="all">{{__('Name')}}</th>
                                <th class="all">{{__('Email')}}</th>
                                <th class="all">{{__('Phone')}}</th>
                                <th class="desktop">{{__('Pocket Imported At')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($co_users[0]))
                                @foreach($co_users as $co_user)
                                    <tr>
                                        <td>{{ $co_user->user->first_name }} {{ $co_user->user->last_name }}
                                            @if($wallet->user_id == $co_user->user->id)
                                            <span class="badge badge-pill badge-warning">{{__('Creator')}}</span>
                                            @endif
                                        </td>
                                        <td>{{ $co_user->user->email }}</td>
                                        <td>{{ $co_user->user->phone }}</td>
                                        <td>{{ $co_user->created_at }}</td>
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
