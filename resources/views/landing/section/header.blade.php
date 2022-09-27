<div class="banner-right-img" >
</div>
<div class="container">
    <div class="banner-wrap">
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <div class="banner-text">
                    <div class="banner-title" data-aos="fade-down" data-aos-duration="2000">
                        <h1>@if(isset($content['landing_title'])) {!!clean($content['landing_title']) !!} @else {{__('Mine Crypto Currency and Earn Coin Easily')}} @endif</h1>
                    </div>
                    <div class="banner-content" data-aos="fade-down" data-aos-duration="3000">
                        <p>
                            @if(isset($content['landing_description'])) {!!clean($content['landing_description']) !!} @else {{__('There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration.There are many There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration')}} @endif
                        </p>
                    </div>
                    <div class="banner-btn" data-aos="fade-down" data-aos-duration="3000">
                        <a href="{{$content['landing_1st_button_link'] ?? ''}}" class="primary-btn">{{$content['landing_1st_button_text'] ?? __('Token Distribution')}}</a>
                        <a href="{{$content['landing_2nd_button_link'] ?? ''}}" class="primary-btn">{{$content['landing_2nd_button_text'] ?? __('Whitepaper')}}</a>
                    </div>
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
