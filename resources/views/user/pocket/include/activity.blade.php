<div class="row">
    <div class="col-lg-12">
        <div class="activity-area">
            <div class="activity-top-area">
                <div class="cp-user-card-header-area">
                    <div class="title">
                        <h4 id="list_title">{{__('All Deposit List')}}</h4>
                    </div>
                    <div class="deposite-tabs cp-user-deposit-card">
                        <div class="activity-right text-right">
                            <ul class="nav cp-user-profile-nav mb-0">
                                <li class="nav-item">
                                    <a class="nav-link  active "
                                       data-toggle="tab"
                                       onclick="$('#list_title').html('All Deposit List')"
                                       data-title=""
                                       href="#Deposit">{{__('Deposit')}}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link @if(isset($ac_tab) && $ac_tab == 'withdraw') active @endif"
                                       data-toggle="tab"
                                       onclick="$('#list_title').html('All Withdrawal List')"
                                       href="#Withdraw">{{__('Withdraw')}}</a>
                                </li>
                                @if(co_wallet_feature_active() && $wallet->type == CO_WALLET)
                                    <li class="nav-item">
                                        <a class="nav-link @if(isset($ac_tab) && $ac_tab == 'co-withdraw') active @endif"
                                           data-toggle="tab"
                                           onclick="$('#list_title').html('Pending Multi-signature Withdrawals')"
                                           href="#co-withdraw">{{__('Pending Multi-signature Withdraw')}}</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="activity-list">
                <div class="tab-content">
                    <div id="Deposit"
                         class="tab-pane fade show active ">

                        <div class="cp-user-wallet-table table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>{{__('Address')}}</th>
                                    <th>{{__('Amount')}}</th>
                                    <th>{{__('Transaction Hash')}}</th>
                                    <th>{{__('Status')}}</th>
                                    <th>{{__('Created At')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($histories[0]))
                                    @foreach($histories as $history)
                                        <tr>
                                            <td>{{$history->address}}</td>
                                            <td>{{$history->amount}}</td>
                                            <td>{{$history->transaction_id}}</td>
                                            <td>{{deposit_status($history->status)}}</td>
                                            <td>{{$history->created_at}}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5"
                                            class="text-center">{{__('No data available')}}</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="Withdraw"
                         class="tab-pane fade @if(isset($ac_tab) && $ac_tab == 'withdraw') show active @endif ">

                        <div class="cp-user-wallet-table table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>{{__('Address')}}</th>
                                    <th>{{__('Amount')}}</th>
                                    <th>{{__('Transaction Hash')}}</th>
                                    <th>{{__('Status')}}</th>
                                    <th>{{__('Created At')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($withdraws[0]))
                                    @foreach($withdraws as $withdraw)
                                        <tr>
                                            <td>{{$withdraw->address}}</td>
                                            <td>{{$withdraw->amount}}</td>
                                            <td>{{$withdraw->transaction_hash}}</td>
                                            <td>{{deposit_status($withdraw->status)}}</td>
                                            <td>{{$withdraw->created_at}}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5"
                                            class="text-center">{{__('No data available')}}</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if(co_wallet_feature_active() && $wallet->type == CO_WALLET)
                        <div id="co-withdraw"
                             class="tab-pane fade @if(isset($ac_tab) && $ac_tab == 'co-withdraw') show active @endif">

                            <div class="cp-user-wallet-table table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>{{__('Address')}}</th>
                                        <th>{{__('Amount')}}</th>
                                        <th>{{__('Status')}}</th>
                                        <th>{{__('Created At')}}</th>
                                        <th>{{__('Actions')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($tempWithdraws[0]))
                                        @foreach($tempWithdraws as $withdraw)
                                            <tr>
                                                <td>{{$withdraw->address}}</td>
                                                <td>{{$withdraw->amount}}</td>
                                                <td>{{__('Need co users approval')}}</td>
                                                <td>{{$withdraw->created_at}}</td>
                                                <td>
                                                    <ul class="d-flex justify-content-center align-items-center">
                                                        <li>
                                                            <a title="{{__('Approvals')}}"
                                                               href="{{route('coWalletApprovals', $withdraw->id)}}">
                                                                <img
                                                                    src="{{asset('assets/user/images/wallet-table-icons/send.svg')}}"
                                                                    class="img-fluid" alt="">
                                                            </a>
                                                        </li>
                                                        @if($withdraw->user_id == \Illuminate\Support\Facades\Auth::id())
                                                            <li>
                                                                <a title="{{__('Reject Withdraw')}}" class="confirm-modal"
                                                                   data-title="{{__('Do you really want to reject?')}}"
                                                                   href="javascript:" data-href="{{route('rejectCoWalletWithdraw', $withdraw->id)}}">
                                                                    <img style="width: 25px; opacity: 0.7"
                                                                         src="{{asset('assets/user/images/close.png')}}"
                                                                         class="img-fluid"
                                                                         alt="">
                                                                </a>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5"
                                                class="text-center">{{__('No data available')}}</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
