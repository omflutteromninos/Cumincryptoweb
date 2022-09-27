<div class="page-title">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-inner">
                <div class="table-title mb-4">
                    <h3>{{__('Landing Page About us Settings')}}</h3>
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
                            <label for="#">{{__('About us 1st paragraph title')}}</label>
                            <input type="text" class="form-control" name="about_1st_title" @if(isset($adm_setting['about_1st_title'])) value="{{$adm_setting['about_1st_title']}}" @endif>
                        </div>
                        <div class="form-group">
                            <label for="#">{{__('About us 1st paragraph description')}}</label>
                            <textarea type="text" class="form-control" name="about_1st_description"> @if(isset($adm_setting['about_1st_description'])) {{$adm_setting['about_1st_description']}} @endif </textarea>
                        </div>
                        <div class="form-group">
                            <label for="#">{{__('About us Image for 1st paragraph')}}</label>
                            <div id="file-upload" class="section-width">
                                <input type="hidden" name="about_1st_logo" value="">
                                <input type="file" placeholder="0.00" name="about_1st_logo" value="" id="file" ref="file"
                                       class="dropify" @if(isset($adm_setting['about_1st_logo'])) data-default-file="{{asset(path_image().$adm_setting['about_1st_logo'])}}"@endif />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="#">{{__('About us 2nd paragraph title')}}</label>
                            <input type="text" class="form-control" name="about_2nd_title" @if(isset($adm_setting['about_2nd_title'])) value="{{$adm_setting['about_2nd_title']}}" @endif>
                        </div>
                        <div class="form-group">
                            <label for="#">{{__('About us 2nd paragraph description')}}</label>
                            <textarea class="form-control" rows="5" name="about_2nd_description">@if(isset($adm_setting['about_2nd_description'])){{$adm_setting['about_2nd_description']}} @endif</textarea>
                        </div>
                        <div class="form-group">
                            <label for="#">{{__('About us Image for 2nd paragraph')}}</label>
                            <div id="file-upload" class="section-width">
                                <input type="hidden" name="about_2nd_logo" value="">
                                <input type="file" placeholder="0.00" name="about_2nd_logo" value="" id="file" ref="file"
                                       class="dropify" @if(isset($adm_setting['about_2nd_logo'])) data-default-file="{{asset(path_image().$adm_setting['about_2nd_logo'])}}"@endif />
                            </div>
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
