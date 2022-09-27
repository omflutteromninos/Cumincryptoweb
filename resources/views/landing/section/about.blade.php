<div class="white_svg svg_white">
    <svg x="0px" y="0px" viewBox="0 0 1920 289" enable-background="new 0 0 1920 289" xml:space="preserve">
        <path fill="#316593" d="M959,169C582.541,169,240.253,104.804-14.125,0H0v289h1920V0h12.125C1677.747,104.804,1335.459,169,959,169
            z"></path>
       </svg>
</div>
<div class="container">
    <div class="row align-items-center about-1">
        <div class="col-lg-6">
            <div class="about-wrap">
                <div class="about-img">
                    <img class="animation-img" src="{{landingPageImage('about_1st_logo','images/banner/ab2.svg')}}" alt="banner" alt="">
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="about-text">
                <h2>
                    @if(isset($content['about_1st_title'])) {!! $content['about_1st_title'] !!} @else {{__('We’ve built a platform to buy and sell shares.')}} @endif
                </h2>
                <p>
                    @if(isset($content['about_1st_description'])) {!!clean($content['about_1st_description']) !!} @else {{__('While existing solutions offer to solve just one problem at a time, our team is up to build a secure, useful, & easy-to-use product based on private blockchain. It will include easy cryptocurrency payments integration, and even a digital arbitration system. At the end, Our aims to integrate all companies, employees, and business assets into a unified blockchain ecosystem, which will make business truly efficient, transparent, and reliable.')}} @endif
                </p>

            </div>
        </div>
    </div>
    <div class="row align-items-center">
        <div class="col-lg-6">
            <div class="about-text">
                <h2>
                    @if(isset($content['about_2nd_title'])) {!! $content['about_2nd_title'] !!} @else {{__('We’ve built a platform to buy and sell shares.')}} @endif
                </h2>
                <p>
                    @if(isset($content['about_2nd_description'])) {!!clean($content['about_2nd_description']) !!} @else {{__('While existing solutions offer to solve just one problem at a time, our team is up to build a secure, useful, & easy-to-use product based on private blockchain. It will include easy cryptocurrency payments integration, and even a digital arbitration system. At the end, Our aims to integrate all companies, employees, and business assets into a unified blockchain ecosystem, which will make business truly efficient, transparent, and reliable.')}} @endif
                </p>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="about-wrap">
                <div class="about-img">
                    <img class="animation-img" src="{{landingPageImage('about_2nd_logo','images/banner/ab3.svg')}}" alt="banner" alt="">
                </div>
            </div>
        </div>
    </div>
</div>
