<div class="page-title">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-inner">
                <div class="table-title mb-4">
                    <h3>{{__('Landing Page roadmap')}}</h3>
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
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="#">{{__('Landing Page Roadmap Title')}}</label>
                            <input type="text" class="form-control" name="landing_roadmap_title" @if(isset($adm_setting['landing_roadmap_title'])) value="{{$adm_setting['landing_roadmap_title']}}" @endif>
                        </div>
                        <div class="form-group">
                            <label for="#">{{__('Landing Page Roadmap Subtitle')}}</label>
                            <textarea type="text" rows="10" class="form-control" name="landing_roadmap_subtitle"> @if(isset($adm_setting['landing_roadmap_subtitle'])){{$adm_setting['landing_roadmap_subtitle']}} @endif </textarea>
                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="#">{{__('1st Roadmap Date')}}</label>
                                    <input type="date" class="form-control" name="roadmap_1st_date" @if(isset($adm_setting['roadmap_1st_date'])) value="{{$adm_setting['roadmap_1st_date']}}" @endif>
                                </div>
                                <div class="form-group">
                                    <label for="#">{{__('1st Roadmap Title')}}</label>
                                    <input type="text" class="form-control" name="roadmap_1st_title" @if(isset($adm_setting['roadmap_1st_title'])) value="{{$adm_setting['roadmap_1st_title']}}" @endif>
                                </div>
                                <div class="form-group">
                                    <label for="#">{{__('1st Roadmap Subtitle')}}</label>
                                    <textarea type="text" class="form-control" name="roadmap_1st_subtitle"> @if(isset($adm_setting['roadmap_1st_subtitle'])){{$adm_setting['roadmap_1st_subtitle']}}@endif </textarea>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="#">{{__('2nd Roadmap Date')}}</label>
                                    <input type="date" class="form-control" name="roadmap_2nd_date" @if(isset($adm_setting['roadmap_2nd_date'])) value="{{$adm_setting['roadmap_2nd_date']}}" @endif>
                                </div>
                                <div class="form-group">
                                    <label for="#">{{__('2nd Roadmap Title')}}</label>
                                    <input type="text" class="form-control" name="roadmap_2nd_title" @if(isset($adm_setting['roadmap_2nd_title'])) value="{{$adm_setting['roadmap_2nd_title']}}" @endif>
                                </div>
                                <div class="form-group">
                                    <label for="#">{{__('2nd Roadmap Subtitle')}}</label>
                                    <textarea type="text" class="form-control" name="roadmap_2nd_subtitle"> @if(isset($adm_setting['roadmap_2nd_subtitle'])){{$adm_setting['roadmap_2nd_subtitle']}}@endif </textarea>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="#">{{__('3rd Roadmap Date')}}</label>
                                    <input type="date" class="form-control" name="roadmap_3rd_date" @if(isset($adm_setting['roadmap_3rd_date'])) value="{{$adm_setting['roadmap_3rd_date']}}" @endif>
                                </div>
                                <div class="form-group">
                                    <label for="#">{{__('3rd Roadmap Title')}}</label>
                                    <input type="text" class="form-control" name="roadmap_3rd_title" @if(isset($adm_setting['roadmap_3rd_title'])) value="{{$adm_setting['roadmap_3rd_title']}}" @endif>
                                </div>
                                <div class="form-group">
                                    <label for="#">{{__('3rd Roadmap Subtitle')}}</label>
                                    <textarea type="text" class="form-control" name="roadmap_3rd_subtitle"> @if(isset($adm_setting['roadmap_3rd_subtitle'])){{$adm_setting['roadmap_3rd_subtitle']}}@endif </textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="#">{{__('4th Roadmap Date')}}</label>
                                    <input type="date" class="form-control" name="roadmap_4th_date" @if(isset($adm_setting['roadmap_4th_date'])) value="{{$adm_setting['roadmap_4th_date']}}" @endif>
                                </div>
                                <div class="form-group">
                                    <label for="#">{{__('4th Roadmap Title')}}</label>
                                    <input type="text" class="form-control" name="roadmap_4th_title" @if(isset($adm_setting['roadmap_4th_title'])) value="{{$adm_setting['roadmap_4th_title']}}" @endif>
                                </div>
                                <div class="form-group">
                                    <label for="#">{{__('4th Roadmap Subtitle')}}</label>
                                    <textarea type="text" class="form-control" name="roadmap_4th_subtitle"> @if(isset($adm_setting['roadmap_4th_subtitle'])){{$adm_setting['roadmap_4th_subtitle']}}@endif </textarea>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="#">{{__('5th Roadmap Date')}}</label>
                                    <input type="date" class="form-control" name="roadmap_5th_date" @if(isset($adm_setting['roadmap_5th_date'])) value="{{$adm_setting['roadmap_5th_date']}}" @endif>
                                </div>
                                <div class="form-group">
                                    <label for="#">{{__('5th Roadmap Title')}}</label>
                                    <input type="text" class="form-control" name="roadmap_5th_title" @if(isset($adm_setting['roadmap_5th_title'])) value="{{$adm_setting['roadmap_5th_title']}}" @endif>
                                </div>
                                <div class="form-group">
                                    <label for="#">{{__('5th Roadmap Subtitle')}}</label>
                                    <textarea type="text" class="form-control" name="roadmap_5th_subtitle"> @if(isset($adm_setting['roadmap_5th_subtitle'])){{$adm_setting['roadmap_5th_subtitle']}}@endif </textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="#">{{__('Current Roadmap Date')}}</label>
                                    <input type="date" class="form-control" name="roadmap_current_date" @if(isset($adm_setting['roadmap_current_date'])) value="{{$adm_setting['roadmap_current_date']}}" @endif>
                                </div>
                                <div class="form-group">
                                    <label for="#">{{__('Current Roadmap Title')}}</label>
                                    <input type="text" class="form-control" name="roadmap_current_title" @if(isset($adm_setting['roadmap_current_title'])) value="{{$adm_setting['roadmap_current_title']}}" @endif>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="#">{{__('Roadmap 1st circle text')}}</label>
                                    <input type="text" class="form-control" name="roadmap_1st_circle_text" @if(isset($adm_setting['roadmap_1st_circle_text'])) value="{{$adm_setting['roadmap_1st_circle_text']}}" @endif>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="#">{{__('Roadmap 2nd circle text')}}</label>
                                    <input type="text" class="form-control" name="roadmap_2nd_circle_text" @if(isset($adm_setting['roadmap_2nd_circle_text'])) value="{{$adm_setting['roadmap_2nd_circle_text']}}" @endif>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="#">{{__('Roadmap 3rd circle text')}}</label>
                                    <input type="text" class="form-control" name="roadmap_3rd_circle_text" @if(isset($adm_setting['roadmap_3rd_circle_text'])) value="{{$adm_setting['roadmap_3rd_circle_text']}}" @endif>
                                </div>
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
