@extends('admin.master',['menu'=>'setting', 'sub_menu'=>'feature'])
@section('title', isset($title) ? $title : '')
@section('style')
@endsection
@section('content')
    <!-- breadcrumb -->
    <div class="custom-breadcrumb">
        <div class="row">
            <div class="col-12">
                <ul>
                    <li>{{__('Settings')}}</li>
                    <li class="active-item">{{ $title }}</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /breadcrumb -->

    <!-- User Management -->
    <div class="user-management">
        <div class="row">
            <div class="col-md-12">
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane show @if(isset($tab) && $tab=='co-pocket')  active @endif" id="co-pocket"
                         role="tabpanel" aria-labelledby="pills-user-tab">
                        <div class="profile-info-form">
                            <form action="{{route('saveAdminFeatureSettings')}}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6 col-12  mt-20">
                                        <div class="header-bar">
                                            <div class="table-title">
                                                <h3>{{__(' Multi-signature Pocket')}}</h3>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="{{CO_WALLET_FEATURE_ACTIVE_SLUG}}">{{' Multi-signature Pocket Feature Status'}}</label>
                                            <br>
                                            <label class="switch">
                                                <input type="checkbox"
                                                       id="{{CO_WALLET_FEATURE_ACTIVE_SLUG}}" name="{{CO_WALLET_FEATURE_ACTIVE_SLUG}}"
                                                       @if(isset($settings[CO_WALLET_FEATURE_ACTIVE_SLUG]) &&
                                                        $settings[CO_WALLET_FEATURE_ACTIVE_SLUG] == STATUS_ACTIVE) checked
                                                       @endif value="{{STATUS_ACTIVE}}">
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <label for="#">{{__('Max Co User For One Pocket')}}</label>
                                            <input class="form-control" type="text" name="{{MAX_CO_WALLET_USER_SLUG}}" required
                                                   placeholder="{{__('5')}}" value="{{$settings[MAX_CO_WALLET_USER_SLUG] ?? ''}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="#">{{__('The (%) Users Approval Needed For A Withdraw')}}</label>
                                            <input class="form-control" type="text" required name="{{CO_WALLET_WITHDRAWAL_USER_APPROVAL_PERCENTAGE_SLUG}}"
                                                   placeholder="{{__('60')}}" value="{{$settings[CO_WALLET_WITHDRAWAL_USER_APPROVAL_PERCENTAGE_SLUG] ?? ''}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-12  mt-20">
                                        <div class="header-bar">
                                            <div class="table-title">
                                                <h3>{{__(' Enable Google Re capcha')}}</h3>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="google_recapcha">{{' Enable Google Re capcha Status'}}</label>
                                            <br>
                                            <label class="switch">
                                                <input type="checkbox"
                                                       id="google_recapcha" name="google_recapcha"
                                                       @if(isset($settings['google_recapcha']) &&
                                                        $settings['google_recapcha'] == STATUS_ACTIVE) checked
                                                       @endif value="{{STATUS_ACTIVE}}">
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <label for="#">{{__('Captcha Secret Key')}}</label>
                                            <input class="form-control" type="text" name="NOCAPTCHA_SECRET"
                                                    value="{{$settings['NOCAPTCHA_SECRET'] ?? ''}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="#">{{__('Captcha Site Key')}}</label>
                                            <input class="form-control" type="text" name="NOCAPTCHA_SITEKEY"
                                                    value="{{$settings['NOCAPTCHA_SITEKEY'] ?? ''}}">
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-12  mt-20">
                                        <div class="header-bar">
                                            <div class="table-title">
                                                <h3>{{__(' Swap Enable/Disable')}}</h3>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="google_recapcha">{{' Enable/Disable Swap Feature'}}</label>
                                            <br>
                                            <label class="switch">
                                                <input type="checkbox"
                                                       id="swap_enabled" name="swap_enabled"
                                                       @if(isset($settings['swap_enabled']) &&
                                                        $settings['swap_enabled'] == STATUS_ACTIVE) checked
                                                       @endif value="{{STATUS_ACTIVE}}">
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                    </div>



                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="col-lg-2 col-12 mt-20">
                                                <button class="button-primary theme-btn">{{__('Save')}}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
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
        // $('.nav-link').on('click', function () {
        //     $('.nav-link').removeClass('active');
        //     $(this).addClass('active');
        //     var str = '#' + $(this).data('controls');
        //     $('.tab-pane').removeClass('show active');
        //     $(str).addClass('show active');
        // });
    </script>

@endsection
