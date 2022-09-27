@extends('landing.master',['menu'=>'dashboard'])
@section('title', isset($title) ? $title : '')
@section('style')
@endsection
@section('content')

{{-- banner --}}
<section class="banner-area" id="banner-area" style="background-image:url({{landingPageImage('landing_page_logo','images/banner/hero.jpg')}});">
    @include('landing.section.header')
</section>
<!-- banner area end here -->
<!-- client-area start -->
<div class="client-area">
    @include('landing.section.coin_list')
</div>
<!-- client-area end -->

<!-- feature area start here -->
<section class="feature-area section" id="features">
    @include('landing.section.feature')
</section>
<!-- feature area end here -->
<!-- about us area start here -->
<section class="about-us-area section" id="about">
    @include('landing.section.about')
</section>
<!-- about us area end here -->
<section id="roadmap" class="section roadmap-area">
    @include('landing.section.roadmap')
</section>
<!-- integration area start here -->
<section class="integration-area section" id="integration">
    @include('landing.section.integration')
</section>
<!-- integration area end here -->
<section id="faq" class="faq-area section">
    @include('landing.section.faq')
</section>
<div id="contact" class="contact-page-area section-padding">
    @include('landing.section.contact')
</div>
@endsection

@section('script')
@endsection
