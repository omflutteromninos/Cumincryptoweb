<div class="white_svg svg_white">
    <svg x="0px" y="0px" viewBox="0 0 1920 289" enable-background="new 0 0 1920 289" xml:space="preserve">
        <path fill="#316593" d="M959,169C582.541,169,240.253,104.804-14.125,0H0v289h1920V0h12.125C1677.747,104.804,1335.459,169,959,169
            z"></path>
       </svg>
</div>
<div class="container">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="contact-page-item">
                <div class="contact-page-title">
                    <h2>
                        @if(isset($content['contact_title'])) {{$content['contact_title']}} @else {{__('Our Contacts')}} @endif
                    </h2>
                    <p>
                        @if(isset($content['contact_sub_title'])) {!!clean($content['contact_sub_title']) !!} @else {{__('Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old.')}} @endif
                    </p>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="adress contact-info-item">
                            <span><img src="{{landingPageImage('address_icon','images/feature/pin.svg')}}" alt=""></span>
                            <h3>
                                @if(isset($content['address_field_title'])) {{$content['address_field_title']}} @else {{__('Address')}} @endif
                            </h3>
                            <span>
                                    @if(isset($content['address_field_details'])) {!!clean($content['address_field_details']) !!} @else {{__('245 King Street, Touterie Victoria 8520 Australia')}} @endif
                                </span>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="phone contact-info-item">
                            <span><img src="{{landingPageImage('phone_icon','images/feature/call.svg')}}" alt=""></span>
                            <h3>
                                @if(isset($content['phone_field_title'])) {{$content['phone_field_title']}} @else {{__('Phone')}} @endif
                            </h3>
                            <span>
                                    @if(isset($content['phone_field_details'])) {!!clean($content['phone_field_details']) !!} @else {{__('0-123-456-7890')}} @endif</span>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="email contact-info-item">
                            <span><img src="{{landingPageImage('email_icon','images/feature/email.svg')}}" alt=""></span>
                            <h3>
                                @if(isset($content['email_field_title'])) {{$content['email_field_title']}} @else {{__('Email')}} @endif
                            </h3>
                            <span>
                                    @if(isset($content['email_field_details'])) {!!clean($content['email_field_details']) !!} @else {{__('sample@gmail.com')}} @endif
                                </span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-lg-12 col-md-12">
            <div class="contact-area contact-area-2 contact-area-3">
                <h2>{{__('Quick Contact Form')}}</h2>
                <div class="contact-form">
                    <form method="post" class="contact-validation-active" action="{{route('ContactUs')}}" id="contact-form" novalidate="novalidate">
                        {{ csrf_field() }}
                        <div class="half-col">
                            <input type="text" name="name" id="name" value="{{old('name')}}" class="form-control" placeholder="{{__('Your Name')}}">
                            <p>@error('name') <span class="text-danger">{{ $message }}</span> @enderror</p>
                        </div>

                        <div class="half-col">
                            <input type="email" name="email" value="{{old('email')}}" id="email" class="form-control" placeholder="{{__('Your Email')}}">
                            <p>@error('email') <span class="text-danger">{{ $message }}</span> @enderror</p>
                        </div>
                        <div class="half-col">
                            <input type="text" name="phone" id="phone" value="{{old('phone')}}" class="form-control" placeholder="{{__('Your Phone')}}">
                            <p>@error('phone') <span class="text-danger">{{ $message }}</span> @enderror</p>
                        </div>
                        <div class="half-col">
                            <input type="text" name="address" id="address" value="{{old('address')}}" class="form-control" placeholder="{{__('Address')}}">
                            <p>@error('address') <span class="text-danger">{{ $message }}</span> @enderror</p>
                        </div>
                        <div>
                            <textarea class="form-control" name="description" id="note" placeholder="{{__('Case Description...')}}">{{old('description')}}</textarea>
                            <p>@error('description') <span class="text-danger">{{ $message }}</span> @enderror</p>
                        </div>
                        <div class="">
                            {!! app('captcha')->display() !!}
                            @error('g-recaptcha-response')
                            <p><span class="text-danger">{{ $message }}</span></p>
                            @enderror
                        </div>
                        <div class="submit-btn-wrapper">
                            <button type="submit" class="primary-btn submit_contact_form">{{__('Submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
