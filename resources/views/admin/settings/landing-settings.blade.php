@extends('admin.master',['menu'=>'setting','sub_menu'=>'landing'])
@section('title', 'Landing Setting')
@section('style')
@endsection
@section('content')
    <!-- coin-area start -->
    <div class="landing-page-area user-management">
        <div class="container-fluid">
            <div class="page-wraper section-padding">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="single-tab section-height">
                            <div class="section-body ">
                                <ul class="nav nav-pills nav-pill-three landing-tab user-management-nav" id="tab" role="tablist">
                                    <li>
                                        <a class="nav-link active" @if(isset($tab) && $tab=='hero')class="active" @endif data-toggle="tab"
                                        href="#hero">{{__('Header Footer')}}</a>
                                    </li>
                                    <li>
                                        <a class="nav-link" @if(isset($tab) && $tab=='about_as')class="active" @endif data-toggle="tab"
                                        href="#about_as">{{__('About us')}}</a>
                                    </li>
                                    <li>
                                        <a class="nav-link" @if(isset($tab) && $tab=='features')class="active" @endif data-toggle="tab"
                                       href="#features">{{__('Features')}}</a>
                                    </li>
                                    <li>
                                        <a class="nav-link" @if(isset($tab) && $tab=='integration')class="active" @endif data-toggle="tab"
                                       href="#integration">{{__('Integration')}}</a>
                                    </li>
                                    <li>
                                        <a class="nav-link" @if(isset($tab) && $tab=='roadmap')class="active" @endif data-toggle="tab"
                                           href="#roadmap">{{__('Roadmap')}}</a>
                                    </li>
                                    <li>
                                        <a class="nav-link" @if(isset($tab) && $tab=='contact')class="active" @endif data-toggle="tab"
                                           href="#contact">{{__('Contact')}}</a>
                                    </li>
                                    <li>
                                        <a class="nav-link" @if(isset($tab) && $tab=='contactUs')class="active" @endif data-toggle="tab"
                                           href="#contactUs">{{__('Received Emails')}}</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <!-- genarel-setting start-->
                                    <div class="tab-pane fade  @if(isset($tab) && $tab=='hero')show active @endif " id="hero" role="tabpanel" aria-labelledby="header-setting-tab">
                                        @include('admin.settings.landing.header')
                                    </div>
                                    <div class="tab-pane fade  @if(isset($tab) && $tab=='roadmap')show active @endif " id="roadmap" role="tabpanel" aria-labelledby="header-setting-tab">
                                        @include('admin.settings.landing.roadmap')
                                    </div>

                                    <!-- genarel-setting start-->
                                    <div class="tab-pane fade  @if(isset($tab) && $tab=='about_as')show active @endif "
                                         id="about_as" role="tabpanel" aria-labelledby="header-setting-tab">
                                        @include('admin.settings.landing.about')
                                    </div>
                                    <div class="tab-pane fade  @if(isset($tab) && $tab=='contact')show active @endif "
                                         id="contact" role="tabpanel" aria-labelledby="header-setting-tab">
                                        @include('admin.settings.landing.contact')
                                    </div>
                                    <div class="tab-pane fade  @if(isset($tab) && $tab=='integration')show active @endif "
                                         id="integration" role="tabpanel" aria-labelledby="header-setting-tab">
                                        @include('admin.settings.landing.integration')
                                    </div>
                                    <div class="tab-pane fade  @if(isset($tab) && $tab=='features')show active @endif "
                                         id="features" role="tabpanel" aria-labelledby="header-setting-tab">
                                        @include('admin.settings.landing.features')
                                    </div>
                                    <div class="tab-pane fade @if(isset($tab) && $tab=='contactUs')show active @endif "
                                         id="contactUs" role="tabpanel" aria-labelledby="header-setting-tab">
                                        @include('admin.settings.landing.contact_form')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Us Modal for description show -->
        <div class="modal fade" id="descriptionModal" tabindex="-1" aria-labelledby="descriptionModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="descriptionModalLabel">{{__('Full Email')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-2"><label>{{__('Name: ')}}</label></div>
                            <div class="col-lg-10">
                                <label>
                                    <input name="name" readonly>
                                </label>
                            </div>
                            <div class="col-lg-2"><label>{{__('Email: ')}}</label></div>
                            <div class="col-lg-10">
                                <label>
                                    <input name="email" readonly>
                                </label>
                            </div>
                            <div class="col-lg-2"><label>{{__('Phone: ')}}</label></div>
                            <div class="col-lg-10">
                                <label>
                                    <input name="phone" readonly>
                                </label>
                            </div>
                            <div class="col-lg-2"><label>{{__('Address: ')}}</label></div>
                            <div class="col-lg-10">
                                <label>
                                    <input name="address" readonly>
                                </label>
                            </div>
                            <div class="col-lg-2"><label>{{__('Description: ')}}</label></div>
                            <div class="col-lg-10">
                                <label>
                                    <div class="description" style="height: 100px;overflow: auto"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                    </div>
                </div>
            </div>
        </div>
@endsection
@section('script')
    <script>
        $('#table').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 10,
            retrieve: true,
            bLengthChange: true,
            responsive: false,
            ajax: '{{route('contactEmailList')}}',
            order: [4, 'desc'],
            autoWidth: false,
            language: {
                paginate: {
                    next: 'Next &#8250;',
                    previous: '&#8249; Previous'
                }
            },
            columns: [
                {"data": "name","orderable": false},
                {"data": "email","orderable": false},
                {"data": "phone","orderable": false},
                {"data": "address","orderable": false},
                {"data": "details","orderable": false}
            ],
        });

        $('#descriptionModal').on('show.bs.modal', function (event) {
            var modal = $(this)
            var button = $(event.relatedTarget) // Button that triggered the modal
            var id = button.data('id') // Extract info from data-* attributes
            $.ajax({
                /* the route pointing to the post function */
                url: "{{route('getDescriptionByID')}}",
                type: 'POST',
                /* send the csrf-token and the input to the controller */
                data: {"_token": "{{ csrf_token() }}", id:id},
                dataType: 'JSON',
                /* remind that 'data' is the response of the AjaxController */
                success: function (data) {
                    console.log(data)
                    console.log(modal.find('.modal-body input[name="name"]'))
                    modal.find('.modal-body input[name="name"]').val(data.name)
                    modal.find('.modal-body input[name="email"]').val(data.email)
                    modal.find('.modal-body input[name="phone"]').val(data.phone)
                    modal.find('.modal-body input[name="address"]').val(data.address)
                    modal.find('.modal-body .description').text(data.description)
                }
            });

        })

        {{--$(document).on('click','.show_details',function (){--}}
        {{--    var id = $(this).data('id');--}}
        {{--    console.log(id)--}}
        {{--    $.ajax({--}}
        {{--        /* the route pointing to the post function */--}}
        {{--        url: "{{route('getDescriptionByID')}}",--}}
        {{--        type: 'POST',--}}
        {{--        /* send the csrf-token and the input to the controller */--}}
        {{--        data: {"_token": "{{ csrf_token() }}", id:id},--}}
        {{--        dataType: 'JSON',--}}
        {{--        /* remind that 'data' is the response of the AjaxController */--}}
        {{--        success: function (data) {--}}
        {{--            console.log(data)--}}
        {{--        }--}}
        {{--    });--}}
        {{--})--}}

    </script>
@endsection
