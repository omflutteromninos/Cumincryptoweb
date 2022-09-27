<div class="header-bar">
    <div class="table-title">
        <h3>{{__('KYC Setup')}}</h3>
    </div>
</div>
<div class="profile-info-form">
    <form action="{{route('adminSaveKycSettings')}}" method="post"
          enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="header-bar">
                    <div class="table-title">
                        <h3>{{__('Withdrawal Section')}}</h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-12  mt-20">
                <div class="form-group">
                    <label>{{__('KYC mandatory for withdrawal')}}</label>
                    <div class="cp-select-area">
                        <select name="kyc_enable_for_withdrawal" class="form-control">
                            <option @if(isset($settings['kyc_enable_for_withdrawal']) && ($settings['kyc_enable_for_withdrawal'] != STATUS_ACTIVE)) selected @endif value={{STATUS_PENDING}}>{{__('Disabled')}}</option>
                            <option @if(isset($settings['kyc_enable_for_withdrawal']) && ($settings['kyc_enable_for_withdrawal'] == STATUS_ACTIVE)) selected @endif value={{STATUS_ACTIVE}}>{{__('Enabled')}}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-12  mt-20">
                <div class="form-group">
                    <label>{{__('NID verification mandatory for withdrawal')}}</label>
                    <div class="cp-select-area">
                        <select name="kyc_nid_enable_for_withdrawal" class="form-control">
                            <option @if(isset($settings['kyc_nid_enable_for_withdrawal']) && ($settings['kyc_nid_enable_for_withdrawal'] != STATUS_ACTIVE)) selected @endif value={{STATUS_PENDING}}>{{__('Disabled')}}</option>
                            <option @if(isset($settings['kyc_nid_enable_for_withdrawal']) && ($settings['kyc_nid_enable_for_withdrawal'] == STATUS_ACTIVE)) selected @endif value={{STATUS_ACTIVE}}>{{__('Enabled')}}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-12  mt-20">
                <div class="form-group">
                    <label>{{__('Driving licence verification mandatory for withdrawal')}}</label>
                    <div class="cp-select-area">
                        <select name="kyc_driving_enable_for_withdrawal" class="form-control">
                            <option @if(isset($settings['kyc_driving_enable_for_withdrawal']) && ($settings['kyc_driving_enable_for_withdrawal'] != STATUS_ACTIVE)) selected @endif value={{STATUS_PENDING}}>{{__('Disabled')}}</option>
                            <option @if(isset($settings['kyc_driving_enable_for_withdrawal']) && ($settings['kyc_driving_enable_for_withdrawal'] == STATUS_ACTIVE)) selected @endif value={{STATUS_ACTIVE}}>{{__('Enabled')}}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-12  mt-20">
                <div class="form-group">
                    <label>{{__('Passport verification mandatory for withdrawal')}}</label>
                    <div class="cp-select-area">
                        <select name="kyc_passport_enable_for_withdrawal" class="form-control">
                            <option @if(isset($settings['kyc_passport_enable_for_withdrawal']) && ($settings['kyc_passport_enable_for_withdrawal'] != STATUS_ACTIVE)) selected @endif value={{STATUS_PENDING}}>{{__('Disabled')}}</option>
                            <option @if(isset($settings['kyc_passport_enable_for_withdrawal']) && ($settings['kyc_passport_enable_for_withdrawal'] == STATUS_ACTIVE)) selected @endif value={{STATUS_ACTIVE}}>{{__('Enabled')}}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 col-12 mt-20">
                <button type="submit" class="button-primary theme-btn">{{__('Update')}}</button>
            </div>
        </div>
    </form>
</div>
