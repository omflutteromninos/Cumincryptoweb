<div class="container">
    <div class="client-item">
        <div class="Gift-carousel owl-carousel">
            @if(isset($coins))
                @foreach($coins as $coin)
                    <div class="client-logo">
                        @if(!empty($coin->coin_icon))
                            <img style="width: 50px; margin: 0 auto; margin-bottom: 13px; object-fit: cover" src="{{show_image_path($coin->coin_icon,'coin/')}}" alt="">
                        @else
                            <i class="fa fa-bitcoin"> </i>
                        @endif
                        {!! clean($coin->name) !!}
                    </div>
                @endforeach
            @else
                <img src="{{landingPageImage('landing_client_logo','images/client/1.png')}}" alt="clientlogo">
                <img src="{{landingPageImage('landing_client_logo','images/client/2.png')}}" alt="clientlogo">
                <img src="{{landingPageImage('landing_client_logo','images/client/3.png')}}" alt="clinet">
                <img src="{{landingPageImage('landing_client_logo','images/client/4.png')}}" alt="clinet">
                <img src="{{landingPageImage('landing_client_logo','images/client/5.png')}}" alt="clinet">
            @endif
        </div>
    </div>
</div>
<div class="white_svg svg_white">
    <svg x="0px" y="0px" viewBox="0 0 1920 289" enable-background="new 0 0 1920 289" xml:space="preserve">
        <path fill="#316593" d="M959,169C582.541,169,240.253,104.804-14.125,0H0v289h1920V0h12.125C1677.747,104.804,1335.459,169,959,169
            z"></path>
       </svg>
</div>
