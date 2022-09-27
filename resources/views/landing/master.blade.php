<!DOCTYPE HTML>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="author" content="itechtheme">
    <meta name="description" content="{{allsetting('app_title')}}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- title here -->
    <title>{{settings('app_title')}}::@yield('title')</title>

    <!-- Favicon and Touch Icons -->
    <link rel="shortcut icon" href="{{landingPageImage('favicon','images/fav.png')}}/">
    <!-- Place favicon.ico in the root directory -->
    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <!-- Fonts -->
    <link href="{{asset('assets/css/gfont.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('landing')}}/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{asset('landing')}}/css/flaticon.css">
    <!-- Plugin CSS -->
    <link rel="stylesheet" href="{{asset('landing')}}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{asset('landing')}}/css/animate.css">
    <link rel="stylesheet" href="{{asset('landing')}}/css/owl.carousel.min.css">
    <link rel="stylesheet" href="{{asset('landing')}}/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="{{asset('landing')}}/css/meanmenu.min.css">
    <link rel="stylesheet" href="{{asset('landing')}}/css/magnific-popup.css">
    <link href="{{asset('landing')}}/css/aos.css" rel="stylesheet">
    {{--for toast message--}}
    <link href="{{asset('assets/toast/vanillatoasts.css')}}" rel="stylesheet" >

    @if (isset(allsetting()['google_recapcha']) && (allsetting()['google_recapcha'] == STATUS_ACTIVE))
        {!! NoCaptcha::renderJs() !!}
    @endif

    <!--Theme custom css -->
    <link rel="stylesheet" href="{{asset('landing')}}/css/style.css">
    <!--Theme Responsive css-->
    <link rel="stylesheet" href="{{asset('landing')}}/css/responsive.css" />
    @yield('style')
    <script src="{{asset('landing')}}/js/vendor/modernizr-3.6.0.min.js"></script>
    {{--toast message--}}
    <script src="{{asset('assets/toast/vanillatoasts.js')}}"></script>
</head>
<body>
<!-- header-area start here -->
<header class="header-area" id="sticky">
    <div class="container">
        <div class="row">
            <div class="md-2 col-2">
                <div class="logo-area" style="color:white;font-size: 30px;">
                    <!--<a href="{{url('/')}}"><img src="{{landingPageImage('logo','images/logo.svg')}}" alt=""></a>-->
                    Cumincrypto
                </div>
            </div>
            <div class="col-md-10">
                <div class="menu-area text-right">
                    <nav class="main-menu">
                        <ul id="nav">
                            <li class="current"><a href="#banner-area">{{__('Home')}}</a></li>
                            <li><a href="#features">{{__('Features')}}</a></li>
                            <li><a href="#about">{{__('About')}}</a></li>
                            <li><a href="#roadmap">{{__('Roadmap')}}</a></li>
                            <li><a href="#integration">{{__('Integration')}}</a></li>
                            <li><a href="#faq">{{__('FAQ')}}</a></li>
                            <li><a href="#contact">{{__('Contact')}}</a></li>
                            @if(Auth::check())
                                <li><a href="{{route('logOut')}}">{{__('Logout')}}</a></li>
                            @else
                                <li><a href="{{route('login')}}">{{__('Login')}}</a></li>
                                <li><a href="{{route('signUp')}}">{{__('Sign up')}}</a></li>
                            @endif
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- header-area end here -->
<!-- banner area start here -->

@yield('content')

<!-- footer area start here -->
<footer class="footer-area">
    <div class="footer-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-30">
                    <div class="single-wedgets text-widget">
                        <div class="footer-logo">
                            <a href="{{url('/')}}"><img src="{{landingPageImage('logo','images/logo.svg')}}" alt=""></a>
                        </div>
                        <div class="widget-text widget-inner">
                            <p>
                                @if(isset($content['footer_description'])) {{$content['footer_description']}} @else
                                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been text ever since.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-30">
                    <div class="single-wedgets text-widget">
                        <div class="widget-title">
                            <h4>{{__('Important Link')}}</h4>
                        </div>
                        <div class="widget-inner">
                            <ul>
                                <li><a href="#banner-area">{{__('Home')}}</a></li>
                                <li><a href="#features">{{__('Feature')}}</a></li>
                                <li><a href="#integration">{{__('Integrations')}}</a></li>
                                <li><a href="#about">{{__('About')}}</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-30">
                    <div class="single-wedgets text-widget">
                        <div class="widget-title">
                            <h4>{{__('Custom Pages')}}</h4>
                        </div>
                        <div class="widget-inner">
                            <ul>
                               @foreach($custom_links as $link)
                                <li><a href="{{route('getCustomPage',[$link->id,str_replace(' ','-',$link->key)])}}">{{$link->key}}</a></li>
                               @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <div class="single-wedgets social-link">
                        <div class="widget-title">
                            <h4>{{__('Social Link')}}</h4>
                        </div>
                        <div class="widget-inner">
                            <ul>
                                <li><a target="_blank" href="@if(isset($content['landing_facebook_link'])) {{$content['landing_facebook_link']}} @else https://facebook.com/ @endif">{{__('Facebook')}}</a></li>
                                @if(isset($content['landing_twitter_link']))
                                    <li><a target="_blank" href="{{$content['landing_twitter_link']}} ">{{__('Twitter')}}</a></li>
                                @endif
                                @if(isset($content['landing_linkedin_link']))
                                    <li><a target="_blank" href=" {{$content['landing_linkedin_link']}}">{{__('Linkedin')}}</a></li>
                                @endif
                                @if(isset($content['landing_youtube_link']))
                                    <li><a target="_blank" href="{{$content['landing_youtube_link']}}">{{__('Youtube')}}</a></li>
                                @endif
                                @if(isset($content['landing_instagram_link']))
                                    <li><a target="_blank" href="{{$content['landing_instagram_link']}}">{{__('Instagram')}}</a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="copyright-area text-center">
                        <p>{{settings('copyright_text') }} <a href="{{url('/')}}">{{ settings('app_title')}}</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- footer area end here -->
<!-- js file start -->
<script src="{{asset('landing')}}/js/vendor/jquery-3.3.1.min.js"></script>
<script src="{{asset('landing')}}/js/plugins.js"></script>
<script src="{{asset('landing')}}/js/Popper.js"></script>
<script src="{{asset('landing')}}/js/bootstrap.min.js"></script>
<script src="{{asset('landing')}}/js/scrollup.js"></script>
<script src="{{asset('landing')}}/js/owl.carousel.min.js"></script>
<script src="{{asset('landing')}}/js/jquery.meanmenu.js"></script>
<script src="{{asset('landing')}}/js/jquery.nav.js"></script>
<script src="{{asset('landing')}}/js/aos.js"></script>
<script src="{{asset('landing')}}/js/image-rotate.js"></script>
<script src="{{asset('landing')}}/js/jquery.magnific-popup.min.js"></script>
<script src="{{asset('landing')}}/js/particleground.js"></script>
<script src="{{asset('landing')}}/js/particle-app.js"></script>
<!-- tweenmax canvas -->
<script type="x-shader/x-vertex" id="wrapVertexShader">
    #define PI 3.1415926535897932384626433832795
    attribute float size;
    void main() {
        vec4 mvPosition = modelViewMatrix * vec4( position, 1.0 );
        gl_PointSize = 3.0;
        gl_Position = projectionMatrix * mvPosition;
    }
</script>
<script type="x-shader/x-fragment" id="wrapFragmentShader">
    uniform sampler2D texture;
    void main(){
        vec4 textureColor = texture2D( texture, gl_PointCoord );
        if ( textureColor.a < 0.3 ) discard;
        // vec4 dotColor = vec4(0.06, 0.18, 0.36, 0.4);

        //vec4 dotColor = vec4(0.06, 0.7, 0.4, 0.7);
        vec4 dotColor = vec4(0.26, 0.17, 0.65, 0.7);
        vec4 color = dotColor * textureColor;
        gl_FragColor = color;
    }
</script>
<script src="{{asset('landing')}}/js/TweenMax.min.js"></script>
<script src="{{asset('landing')}}/js/three.min.js"></script>
<script src="{{asset('landing')}}/js/main.js"></script>

<script>
    if ($(".particleground").length) {
        $('.particleground').particleground({
            dotColor: '#999999',
            lineColor: '#999999',
            particleRadius: 5,
            lineWidth: 2,
            curvedLines: true,
            proximity: 20,
            parallaxMultiplier: 10,
        });
    }

</script>
@if(session()->has('success'))
    <script>
        window.onload = function () {
            VanillaToasts.create({
                //  title: 'Message Title',
                text: '{{session('success')}}',
                backgroundColor: "linear-gradient(135deg, #73a5ff, #5477f5)",
                type: 'success',
                timeout: 10000
            });
        }

    </script>

@elseif(session()->has('dismiss'))
    <script>
        window.onload = function () {

            VanillaToasts.create({
                // title: 'Message Title',
                text: '{{session('dismiss')}}',
                type: 'warning',
                timeout: 10000

            });
        }
    </script>

@elseif($errors->any())
    @foreach($errors->getMessages() as $error)
        <script>
            window.onload = function () {
                VanillaToasts.create({
                    // title: 'Message Title2',
                    text: '{{ $error[0] }}',
                    type: 'warning',
                    timeout: 10000

                });
            }
        </script>

        @break
    @endforeach

@endif
@yield('script')
<!-- End js file -->
</body>
</html>

