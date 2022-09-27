<div class="page-title">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-inner">
                <div class="table-title mb-4">
                    <h3>{{__('Integration Settings')}}</h3>
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
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="#">{{__('Landing Page Integration Title')}}</label>
                            <input type="text" class="form-control" name="landing_integration_title"
                                   @if(isset($adm_setting['landing_integration_title']))value="{{$adm_setting['landing_integration_title']}}" @endif>
                        </div>
                        <div class="form-group">
                            <label for="#">{{__('Landing Page Integration Button Link')}}</label>
                            <input type="text" class="form-control"
                                   name="landing_integration_button_link"
                                   @if(isset($adm_setting['landing_integration_button_link']))value="{{$adm_setting['landing_integration_button_link']}}" @endif>
                        </div>

                        <div class="form-group">
                            <label for="#">{{__('Landing Page Integration Description')}}</label>
                            <textarea class="form-control" rows="5"
                                      name="landing_integration_description">@if(isset($adm_setting['landing_integration_description'])){{$adm_setting['landing_integration_description']}} @endif</textarea>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">

                                    <label for="#">{{__('Landing Page integration Image')}}</label>
                                    <div id="file-upload" class="section-width">
                                        <input type="hidden" name="landing_integration_page_logo" value="">
                                        <input type="file" placeholder="0.00" name="landing_integration_page_logo" value="" id="file" ref="file"
                                               class="dropify" @if(isset($adm_setting['landing_integration_page_logo'])) data-default-file="{{asset(path_image().$adm_setting['landing_integration_page_logo'])}}"@endif />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="button-primary theme-btn">{{__('Update')}}</button>
                    </div>

                </div>
            </div>
        </div>
    </form>
</div>
