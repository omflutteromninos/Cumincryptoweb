<div class="page-title">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-inner">
                <div class="table-title mb-4">
                    <h3>{{__('Landing Page Features Settings')}}</h3>
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
            @if(isset($itech))
                <input type="hidden" name="itech" value="{{$itech}}">
            @endif
            <div class="col-12">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="#">{{__('Landing Page Features Title')}}</label>
                            <input type="text" class="form-control" name="landing_feature_title"
                                   @if(isset($adm_setting['landing_feature_title']))value="{{$adm_setting['landing_feature_title']}}" @endif>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="#">{{__('Landing feature subtitle')}}</label>
                            <input type="text" class="form-control" name="landing_feature_subtitle"
                                   @if(isset($adm_setting['landing_feature_subtitle']))value="{{$adm_setting['landing_feature_subtitle']}}" @endif>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="#">{{__('1st feature title')}}</label>
                                    <input type="text" class="form-control" name="1st_feature_title"
                                           @if(isset($adm_setting['1st_feature_title']))value="{{$adm_setting['1st_feature_title']}}" @endif>
                                </div>
                                <div class="form-group">
                                    <label for="#">{{__('1st feature subtitle')}}</label>
                                    <input type="text" class="form-control"
                                           name="1st_feature_subtitle"
                                           @if(isset($adm_setting['1st_feature_subtitle']))value="{{$adm_setting['1st_feature_subtitle']}}" @endif>
                                </div>

                                <div class="form-group">
                                    <label for="#">{{__('1st feature image')}}</label>
                                    <div id="file-upload" class="section-width">
                                        <input type="hidden" name="1st_feature_icon" value="">
                                        <input type="file" placeholder="0.00" name="1st_feature_icon" value="" id="file" ref="file"
                                               class="dropify" @if(isset($adm_setting['1st_feature_icon'])) data-default-file="{{asset(path_image().$adm_setting['1st_feature_icon'])}}"@endif />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="#">{{__('2nd feature title')}}</label>
                                    <input type="text" class="form-control" name="2nd_feature_title"
                                           @if(isset($adm_setting['2nd_feature_title']))value="{{$adm_setting['2nd_feature_title']}}" @endif>
                                </div>
                                <div class="form-group">
                                    <label for="#">{{__('2nd feature subtitle')}}</label>
                                    <input type="text" class="form-control"
                                           name="2nd_feature_subtitle"
                                           @if(isset($adm_setting['2nd_feature_subtitle']))value="{{$adm_setting['2nd_feature_subtitle']}}" @endif>
                                </div>

                                <div class="form-group">
                                    <label for="#">{{__('2nd feature image')}}</label>
                                    <div id="file-upload" class="section-width">
                                        <input type="hidden" name="2nd_feature_icon" value="">
                                        <input type="file" placeholder="0.00" name="2nd_feature_icon" value="" id="file" ref="file"
                                               class="dropify" @if(isset($adm_setting['2nd_feature_icon'])) data-default-file="{{asset(path_image().$adm_setting['2nd_feature_icon'])}}"@endif />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="#">{{__('3rd feature title')}}</label>
                                    <input type="text" class="form-control" name="3rd_feature_title"
                                           @if(isset($adm_setting['3rd_feature_title']))value="{{$adm_setting['3rd_feature_title']}}" @endif>
                                </div>
                                <div class="form-group">
                                    <label for="#">{{__('3rd feature subtitle')}}</label>
                                    <input type="text" class="form-control" name="3rd_feature_subtitle"
                                           @if(isset($adm_setting['3rd_feature_subtitle']))value="{{$adm_setting['3rd_feature_subtitle']}}" @endif>
                                </div>

                                <div class="form-group">
                                    <label for="#">{{__('3rd feature image')}}</label>
                                    <div id="file-upload" class="section-width">
                                        <input type="hidden" name="3rd_feature_icon" value="">
                                        <input type="file" placeholder="0.00" name="3rd_feature_icon" value="" id="file" ref="file"
                                               class="dropify" @if(isset($adm_setting['3rd_feature_icon'])) data-default-file="{{asset(path_image().$adm_setting['3rd_feature_icon'])}}"@endif />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="button-primary theme-btn">{{__('Update')}}</button>
                </div>
            </div>
        </div>
    </form>
</div>
