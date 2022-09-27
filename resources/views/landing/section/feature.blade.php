<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="section-title">
                <h2>
                    @if(isset($content['landing_feature_title'])) {!! $content['landing_feature_title'] !!} @else {{__('Why Choose Cpocket ?')}} @endif
                </h2>
                <span class="separator"></span>
                <p>
                    @if(isset($content['landing_feature_subtitle'])) {!!clean($content['landing_feature_subtitle']) !!} @else {{__('There are many variations of passages of Lorem Ipsum available, but the majority')}} @endif
                </p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 col-md-6 mb-30">
            <div class="single-feature text-center" data-aos="fade-up" data-aos-duration="2000">
                <div class="feature-icon">
                    <img src="{{landingPageImage('1st_feature_icon','images/feature/1.svg')}}" alt="">
                </div>
                <div class="feature-title">
                    <h3>
                        @if(isset($content['1st_feature_title'])) {!! $content['1st_feature_title'] !!} @else {{__('Instant Exchange')}} @endif
                    </h3>
                </div>
                <div class="feature-content">
                    <p>
                        @if(isset($content['1st_feature_subtitle'])) {!!clean($content['1st_feature_subtitle']) !!} @else {{__('The point of using Lorem Ipsum is that it has or-less normal distribution letters, as opposed Content here.')}} @endif
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="single-feature text-center mb-3" data-aos="fade-up" data-aos-duration="2500">
                <div class="feature-icon">
                    <img src="{{landingPageImage('2nd_feature_icon','images/feature/2.svg')}}" alt="">
                </div>
                <div class="feature-title">
                    <h3>
                        @if(isset($content['2nd_feature_title'])) {!! $content['2nd_feature_title'] !!} @else {{__('Instant Cashout')}} @endif
                    </h3>
                </div>
                <div class="feature-content">
                    <p>
                        @if(isset($content['2nd_feature_subtitle'])) {!!clean($content['2nd_feature_subtitle']) !!} @else {{__('The point of using Lorem Ipsum is that it has or-less normal distribution letters, as opposed Content here.')}} @endif
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="single-feature text-center mb-3" data-aos="fade-up" data-aos-duration="2500">
                <div class="feature-icon">
                    <img src="{{landingPageImage('3rd_feature_icon','images/feature/3.svg')}}" alt="">
                </div>
                <div class="feature-title">
                    <h3>
                        @if(isset($content['3rd_feature_title'])) {!! $content['3rd_feature_title'] !!} @else {{__('Safe & Secure')}} @endif
                    </h3>
                </div>
                <div class="feature-content">
                    <p>
                        @if(isset($content['3rd_feature_subtitle'])) {!!clean($content['3rd_feature_subtitle']) !!} @else {{__('The point of using Lorem Ipsum is that it has or-less normal distribution letters, as opposed Content here.')}} @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="white_svg svg_white">
    <svg x="0px" y="0px" viewBox="0 0 1920 289" enable-background="new 0 0 1920 289" xml:space="preserve">
        <path fill="#0f4571" d="M959,169C582.541,169,240.253,104.804-14.125,0H0v289h1920V0h12.125C1677.747,104.804,1335.459,169,959,169
            z"></path>
       </svg>
</div>
