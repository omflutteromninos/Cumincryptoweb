<div class="header-bar">
    <div class="table-title">
        <h3>{{__('Referral Settings')}}</h3>
    </div>
</div>
<div class="profile-info-form">
    <form method="post" action="{{route('adminReferralFeesSettings')}}">
        @csrf
        <div class="row">
            <div class="col-lg-12 col-12  mt-20">
                <div class="form-group">
                    <label
                        class="">{{__('Maximum Affiliation Level : ') }} 3</label>
                </div>
            </div>
            <div class="col-lg-6 col-12  mt-20">
                <div class="form-group">
                    <label class="">{{__('Referral reward for signup') }}</label>
                    <input type="text" class="form-control number_only"
                           name="referral_signup_reward" min="0"
                           value="{{ old('referral_signup_reward', isset($settings['referral_signup_reward']) ? $settings['referral_signup_reward'] : 100) }}">
                </div>
            </div>
            @for($i = 1; $i <=3 ; $i ++)
                <div class="col-lg-6 col-12  mt-20">
                    <div class="form-group">
                        <label for="#">{{ __('Level') }} {{$i}} (%)</label>
                        @php( $slug_name = 'fees_level'.$i)
                        <p class="fees-wrap">
                            <input type="text" class="number_only form-control"
                                   name="{{$slug_name}}"
                                   value="{{ old($slug_name, isset($settings[$slug_name]) ? $settings[$slug_name] : 0) }}">
                            <span>%</span>
                        </p>
                    </div>
                </div>
            @endfor
        </div>
        <div class="row">
            <div class="col-lg-2 col-12 mt-20">
                <button class="button-primary theme-btn">{{__('Update')}}</button>
            </div>
        </div>
    </form>
    <!-- Fees Settings end-->
</div>
