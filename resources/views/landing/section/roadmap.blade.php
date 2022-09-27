<div class="white_svg svg_white">
    <svg x="0px" y="0px" viewBox="0 0 1920 289" enable-background="new 0 0 1920 289" xml:space="preserve">
        <path fill="#0f4571" d="M959,169C582.541,169,240.253,104.804-14.125,0H0v289h1920V0h12.125C1677.747,104.804,1335.459,169,959,169
            z"></path>
       </svg>
</div>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="section-title">
                <h2>
                    @if(isset($content['landing_roadmap_title'])) {{$content['landing_roadmap_title'] }} @else {{__('Project Roadmap')}} @endif
                </h2>
                <span class="separator"></span>
                <p>
                    @if(isset($content['landing_roadmap_subtitle'])) {!!clean($content['landing_roadmap_subtitle']) !!} @else {{__('There are many variations of passages of Lorem Ipsum available, but the majority')}} @endif
                </p>
            </div>
        </div>
    </div>
    <div class="timeline">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="timeline-container">
                        <div class="timeline-end">
                            <p>
                                @if(isset($content['roadmap_1st_circle_text'])) {{($content['roadmap_1st_circle_text']) }} @else {{__('Now')}} @endif
                            </p>
                        </div>
                        <div class="timeline-continue">

                            <div class="row timeline-right">
                                <div class="col-md-6">
                                    <p class="timeline-date">
                                        @if(isset($content['roadmap_1st_date'])) {{($content['roadmap_1st_date']) }} @else {{__('JUNE 2020')}} @endif
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <div class="timeline-box">
                                        <div class="timeline-icon">
                                            <i class="fa fa-file-code-o" aria-hidden="true"></i>
                                        </div>
                                        <div class="timeline-text">
                                            <h3>@if(isset($content['roadmap_1st_title'])) {!! $content['roadmap_1st_title'] !!} @else {{__('Project Concept')}} @endif</h3>
                                            <p>
                                                @if(isset($content['roadmap_1st_subtitle'])) {!!clean($content['roadmap_1st_subtitle']) !!} @else {{__('Lorem ipsum dolor sit amet, consectetur adipiscing elit.')}} @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row timeline-left">
                                <div class="col-md-6 d-md-none d-block">
                                    <p class="timeline-date dfgderg">
                                        @if(isset($content['roadmap_2nd_date'])) {{($content['roadmap_2nd_date']) }} @else {{__('SEPTEMBER 2020')}}@endif
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <div class="timeline-box">
                                        <div class="timeline-icon d-md-none d-block">
                                            <i class="fa fa-bullhorn" aria-hidden="true"></i>
                                        </div>
                                        <div class="timeline-text">
                                            <h3>
                                                @if(isset($content['roadmap_2nd_title'])) {!! $content['roadmap_2nd_title'] !!} @else {{__('Platform Launch')}} @endif
                                            </h3>
                                            <p>
                                                @if(isset($content['roadmap_2nd_subtitle'])) {!!clean($content['roadmap_2nd_subtitle']) !!} @else {{__('Lorem ipsum dolor sit amet, consectetur adipiscing elit.')}} @endif
                                            </p>
                                        </div>
                                        <div class="timeline-icon d-md-block d-none">
                                            <i class="fa fa-bullhorn" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 d-md-block d-none">
                                    <p class="timeline-date">
                                        @if(isset($content['roadmap_2nd_date'])) {{($content['roadmap_2nd_date']) }} @else {{__('SEPTEMBER 2020')}}@endif
                                    </p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="timeline-year">
                                        <p>
                                            @if(isset($content['roadmap_2nd_circle_text'])) {{($content['roadmap_2nd_circle_text']) }} @else {{__('2020')}} @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="row timeline-right">
                                <div class="col-md-6">
                                    <p class="timeline-date">
                                        @if(isset($content['roadmap_3rd_date'])) {{($content['roadmap_3rd_date']) }} @else {{__('January 2020')}}@endif
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <div class="timeline-box">
                                        <div class="timeline-icon">
                                            <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                        </div>
                                        <div class="timeline-text">
                                            <h3>
                                                @if(isset($content['roadmap_3rd_title'])) {!! $content['roadmap_3rd_title'] !!} @else {{__('Published Whitepaper')}} @endif
                                            </h3>
                                            <p>
                                                @if(isset($content['roadmap_3rd_subtitle'])) {!!clean($content['roadmap_3rd_subtitle']) !!} @else {{__('Lorem ipsum dolor sit amet, consectetur adipiscing elit.')}} @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row timeline-left">
                                <div class="col-md-6 d-md-none d-block">
                                    <p class="timeline-date">
                                        @if(isset($content['roadmap_4th_date'])) {{($content['roadmap_4th_date']) }} @else {{__('March 2020')}}@endif
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <div class="timeline-box">
                                        <div class="timeline-icon d-md-none d-block">
                                            <i class="fa fa-credit-card" aria-hidden="true"></i>
                                        </div>
                                        <div class="timeline-text">
                                            <h3>
                                                @if(isset($content['roadmap_4th_title'])) {!! $content['roadmap_4th_title'] !!} @else {{__('First Pre-Sale')}} @endif
                                            </h3>
                                            <p>
                                                @if(isset($content['roadmap_4th_subtitle'])) {!!clean($content['roadmap_4th_subtitle']) !!} @else {{__('Lorem ipsum dolor sit amet, consectetur adipiscing elit.')}} @endif
                                            </p>
                                        </div>
                                        <div class="timeline-icon d-md-block d-none">
                                            <i class="fa fa-credit-card" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 d-md-block d-none">
                                    <p class="timeline-date">
                                        @if(isset($content['roadmap_4th_date'])) {{($content['roadmap_4th_date']) }} @else {{__('March 2020')}}@endif
                                    </p>
                                </div>
                            </div>

                            <div class="row timeline-right">
                                <div class="col-md-6">
                                    <p class="timeline-date">
                                        @if(isset($content['roadmap_5th_date'])) {{($content['roadmap_5th_date']) }} @else {{__('May 2020')}}@endif
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <div class="timeline-box">
                                        <div class="timeline-icon">
                                            <i class="fa fa-mobile" aria-hidden="true"></i>
                                        </div>
                                        <div class="timeline-text">
                                            <h3>
                                                @if(isset($content['roadmap_5th_title'])) {!! $content['roadmap_5th_title'] !!} @else {{__('Mobile App Release')}} @endif
                                            </h3>
                                            <p>
                                                @if(isset($content['roadmap_5th_subtitle'])) {!!clean($content['roadmap_5th_subtitle']) !!} @else {{__('Lorem ipsum dolor sit amet, consectetur adipiscing elit.')}} @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="timeline-start">
                            <p>
                                @if(isset($content['roadmap_3rd_circle_text'])) {{($content['roadmap_3rd_circle_text']) }} @else {{__('Launch')}} @endif
                            </p>
                        </div>
                        <div class="timeline-launch">
                            <div class="timeline-box">
                                <div class="timeline-text">
                                    <h3>@if(isset($content['roadmap_current_date'])) {!!clean($content['roadmap_current_date']) !!} @else {{__('Now')}}@endif</h3>
                                    <p>@if(isset($content['roadmap_current_title'])) {!!clean($content['roadmap_current_title']) !!} @else {{__('ICO Launch')}}@endif</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
