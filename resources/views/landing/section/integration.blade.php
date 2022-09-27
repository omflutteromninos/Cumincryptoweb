<div class="container">
    <div class="integration-top">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-12">
                <div class="integration-info mb-30">
                    <h2>
                        @if(isset($content['landing_integration_title'])) {!! $content['landing_integration_title'] !!} @else {{__('Easy Customization & Secure Payment System.')}} @endif
                    </h2>
                    <p>
                        @if(isset($content['landing_integration_description'])) {!!clean($content['landing_integration_description']) !!} @else {{__('Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy when Lorem Ipsum is simply dummy text of the printing and typesetting industry I completely follow all your instructions.')}} @endif
                    </p>
                    <a href="{{$content['landing_integration_button_link'] ?? '' }}" class="primary-btn">{{__('Know More')}}</a>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="integration-img">
                    <img data-aos="zoom-in" class="animation-img" data-aos-duration="3000" src="{{landingPageImage('landing_integration_page_logo','images/banner/ab3.png')}}" alt="integration">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="white_svg svg_white">
    <svg x="0px" y="0px" viewBox="0 0 1920 289" enable-background="new 0 0 1920 289" xml:space="preserve">
        <path fill="#316593" d="M959,169C582.541,169,240.253,104.804-14.125,0H0v289h1920V0h12.125C1677.747,104.804,1335.459,169,959,169
            z"></path>
       </svg>
</div>
