<div class="page-title">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-inner">
                <div class="table-title mb-4">
                    <h3>{{__('Landing Page Settings')}}</h3>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="form-area plr-65 profile-info-form">
    <form enctype="multipart/form-data" method="POST" action="{{route('adminLandingSettingSave')}}">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="#">{{__('Landing Page Title')}}</label>
                            <input class="form-control" type="text" name="landing_title" @if(isset($adm_setting['landing_title'])) value="{{$adm_setting['landing_title']}}" @endif>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="#">{{__('Landing Page Description')}}</label>
                            <textarea class="form-control" rows="5" name="landing_description">@if(isset($adm_setting['landing_description'])){{$adm_setting['landing_description']}} @endif</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="#">{{__('Landing Page 1st button text')}}</label>
                            <input class="form-control" type="text" name="landing_1st_button_text" @if(isset($adm_setting['landing_1st_button_text'])) value="{{$adm_setting['landing_1st_button_text']}}" @endif>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="#">{{__('Landing Page 1st button Link')}}</label>
                            <input class="form-control" type="text" name="landing_1st_button_link" @if(isset($adm_setting['landing_1st_button_link'])) value="{{$adm_setting['landing_1st_button_link']}}" @endif>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="#">{{__('Landing Page 2nd button text')}}</label>
                            <input class="form-control" type="text" name="landing_2nd_button_text" @if(isset($adm_setting['landing_2nd_button_text'])) value="{{$adm_setting['landing_2nd_button_text']}}" @endif>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="#">{{__('Landing Page 2nd button link')}}</label>
                            <input class="form-control" type="text" name="landing_2nd_button_link" @if(isset($adm_setting['landing_2nd_button_link'])) value="{{$adm_setting['landing_2nd_button_link']}}" @endif>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="#">{{__('Footer Short Description')}}</label>
                            <textarea class="form-control" rows="5" name="footer_description">@if(isset($adm_setting['footer_description'])){{$adm_setting['footer_description']}} @endif</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="#">{{__('Facebook link')}}</label>
                            <input class="form-control" type="text" name="landing_facebook_link" @if(isset($adm_setting['landing_facebook_link'])) value="{{$adm_setting['landing_facebook_link']}}" @endif>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="#">{{__('Twitter link')}}</label>
                            <input class="form-control" type="text" name="landing_twitter_link" @if(isset($adm_setting['landing_twitter_link'])) value="{{$adm_setting['landing_twitter_link']}}" @endif>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="#">{{__('Linkedin link')}}</label>
                            <input class="form-control" type="text" name="landing_linkedin_link" @if(isset($adm_setting['landing_linkedin_link'])) value="{{$adm_setting['landing_linkedin_link']}}" @endif>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="#">{{__('Youtube link')}}</label>
                            <input class="form-control" type="text" name="landing_youtube_link" @if(isset($adm_setting['landing_youtube_link'])) value="{{$adm_setting['landing_youtube_link']}}" @endif>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="#">{{__('Instagram link')}}</label>
                            <input class="form-control" type="text" name="landing_instagram_link" @if(isset($adm_setting['landing_instagram_link'])) value="{{$adm_setting['landing_instagram_link']}}" @endif>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">

                            <label for="#">{{__('Landing Page Image')}}</label>
                            <div id="file-upload" class="section-width">
                                <input type="hidden" name="landing_page_logo" value="">
                                <input type="file" placeholder="0.00" name="landing_page_logo"
                                       value="" id="file" ref="file" class="dropify" @if(isset($adm_setting['landing_page_logo']) && (!empty($adm_setting['landing_page_logo']))) data-default-file="{{asset(path_image().$adm_setting['landing_page_logo'])}}" @endif />
                            </div>
                        </div>
                    </div>
                </div>
                @if(isset($itech))
                    <input type="hidden" name="itech" value="{{$itech}}">
                @endif
                <button class="button-primary theme-btn">{{__('Update')}}</button>
            </div>
        </div>
    </form>
</div>
