<div class="page-title">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-inner">
                <div class="table-title mb-4">
                    <h3>{{__('Landing Page Contact Settings')}}</h3>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="form-area plr-65 profile-info-form">
    <form enctype="multipart/form-data" method="POST"
          action="{{route('adminLandingSettingSave')}}">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="#">{{__('Contact title')}}</label>
                            <input type="text" class="form-control" name="contact_title" @if(isset($adm_setting['contact_title'])) value="{{$adm_setting['contact_title']}}" @endif>
                        </div>
                        <div class="form-group">
                            <label for="#">{{__('Contact sub-title')}}</label>
                            <input type="text" class="form-control" name="contact_sub_title" @if(isset($adm_setting['contact_sub_title'])) value="{{$adm_setting['contact_sub_title']}}" @endif>
                        </div>
                        <div class="form-group">
                            <label for="#">{{__('Address field title')}}</label>
                            <input type="text" class="form-control" name="address_field_title" @if(isset($adm_setting['address_field_title'])) value="{{$adm_setting['address_field_title']}}" @endif>
                        </div>
                        <div class="form-group">
                            <label for="#">{{__('Address field details')}}</label>
                            <textarea class="form-control" rows="5" name="address_field_details">@if(isset($adm_setting['address_field_details'])){{$adm_setting['address_field_details']}} @endif</textarea>
                        </div>
                        <div class="form-group">
                            <label for="#">{{__('Phone field title')}}</label>
                            <input type="text" class="form-control" name="phone_field_title" @if(isset($adm_setting['phone_field_title'])) value="{{$adm_setting['phone_field_title']}}" @endif>
                        </div>
                        <div class="form-group">
                            <label for="#">{{__('Phone field details')}}</label>
                            <textarea class="form-control" rows="5" name="phone_field_details">@if(isset($adm_setting['phone_field_details'])){{$adm_setting['phone_field_details']}} @endif</textarea>
                        </div>
                        <div class="form-group">
                            <label for="#">{{__('Email field title')}}</label>
                            <input type="text" class="form-control" name="email_field_title" @if(isset($adm_setting['email_field_title'])) value="{{$adm_setting['email_field_title']}}" @endif>
                        </div>
                        <div class="form-group">
                            <label for="#">{{__('Email field details')}}</label>
                            <input class="form-control" name="email_field_details" @if(isset($adm_setting['email_field_details'])) value="{{$adm_setting['email_field_details']}}" @endif>
                        </div>
                        @if(isset($itech))
                            <input type="hidden" name="itech" value="{{$itech}}">
                        @endif
                        <button class="button-primary theme-btn">{{__('Update')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
