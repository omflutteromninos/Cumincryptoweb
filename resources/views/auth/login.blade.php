@extends('auth.master',['menu'=>'dashboard'])
@section('title', isset($title) ? $title : '')

@section('content')
    <div class="user-content-wrapper">
        <div>
            <div class="user-form">
                <div class="right">
                    <div class="form-top">
                        <a class="auth-logo" href="javascript:">
                            <img src="{{show_image(1,'login_logo')}}" class="img-fluid" alt="">
                        </a>
                        {{--                    <h2>{{__('Login')}}</h2>--}}
                        <p>{{__('Log into your account')}}</p>
                        <div class="user-icon">
                            <img src="{{asset('assets/img/user.svg')}}" alt="user icon">
                        </div>
                    </div>
                    {{Form::open(['route' => 'loginProcess', 'files' => true])}}
                    <div class="form-group">
                        <label>{{__('Email address')}}</label>
                        <input type="email" value="{{old('email')}}" id="exampleInputEmail1" name="email"
                               class="form-control" placeholder="{{__('Your email here')}}">
                        @error('email')
                        <p class="invalid-feedback">{{ $message }} </p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>{{__('Password')}}</label>
                        <input type="password" name="password" id="exampleInputPassword1"
                               class="form-control form-control-password look-pass-a"
                               placeholder="{{__('Your password here')}}">
                        @error('password')
                        <p class="invalid-feedback">{{ $message }} </p>
                        @enderror
                        <span class="eye"><i class="fa fa-eye-slash toggle-password"
                                             onclick="showHidePassword('old_password')"></i></span>
                    </div>
                    <div class="form-group">
                        <label>{{__('')}}</label>
                        {!! app('captcha')->display() !!}
                        @error('g-recaptcha-response')
                        <p class="invalid-feedback">{{ $message }} </p>
                        @enderror
                    </div>
                    <div class="d-flex justify-content-between rememberme align-items-center mb-4">
                        <div>
                            <div class="form-group form-check mb-0">
                                <input class="styled-checkbox form-check-input" id="styled-checkbox-1" type="checkbox"
                                       value="value1">
{{--                                <label for="styled-checkbox-1">{{__('Remember Me')}}</label>--}}
                            </div>
                        </div>
                        <div class="text-right"><a href="{{route('forgotPassword')}}">{{__('Forgot Password?')}}</a>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary nimmu-user-sibmit-button">{{__('Login')}}</button>
                    {{Form::close()}}
                    <div class="form-bottom text-center">
                        <p>{{__("Don't have an account?")}} <a href="{{route('signUp')}}">{{__('Sign Up')}}</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')


    <script>
        $(".toggle-password").click(function () {
            $(this).toggleClass("fa-eye-slash fa-eye");
        });

        $(".eye").on('click', function () {
            var $pwd = $(".look-pass-a");
            if ($pwd.attr('type') === 'password') {
                $pwd.attr('type', 'text');
            } else {
                $pwd.attr('type', 'password');
            }
        });
    </script>
@endsection
