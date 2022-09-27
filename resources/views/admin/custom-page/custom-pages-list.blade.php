@extends('admin.master',['menu'=>'setting', 'sub_menu'=>'custom_pages'])
@section('title', isset($title) ? $title : '')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/customPage/jquery-ui.css')}}">
@endsection
@section('content')
    <div class="custom-breadcrumb">
        <div class="row">
            <div class="col-12">
                <ul>
                    <li>{{__('Settings')}}</li>
                    <li class="active-item">{{__('Custom Pages')}} </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="user-management padding-30">
        <div class="row">
            <div class="col-12">
                <div class="header-bar">
                    <div class="table-title">
                        <h3>{{__('Custom Pages')}}</h3>
                    </div>
                    <div class="right d-flex align-items-center">
                        <div class="add-btn">
                            <a href="{{route('adminCustomPageAdd')}}">{{__('+ Add New Page')}}</a>
                        </div>
                    </div>
                </div>
                <div class="table-area">
                    <div>
                        <table class="table" id="table">
                            <thead>
                            <tr>
                                <th>{{__('Menu')}}</th>
                                <th>{{__('Title')}}</th>
                                <th>{{__('Created At')}}</th>
                                <th>{{__('Actions')}}</th>
                            </tr>
                            </thead>
                            <tbody id="sortable"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- .user-area end -->
@endsection
@section('script')
    <script>
        $('#table').DataTable({
            processing: true,
            serverSide: true,
//            pageLength: 10,
            responsive: true,
            ajax: '{{route('adminCustomPageList')}}',
            order: [2, 'desc'],
            autoWidth:false,
            language: {
                paginate: {
                    next: 'Next &#8250;',
                    previous: '&#8249; Previous'
                }
            },
            columns: [
                {"data": "key"},
                {"data": "title"},

                {"data": "created_at"},
                {"data": "actions"}
            ]
        });
    </script>
    <script src="{{asset('assets/customPage/jquery-1.12.4.js')}}"></script>
    <script src="{{asset('assets/customPage/jquery-ui.js')}}"></script>
    <script>
        $( function() {
            $( "#sortable" ).sortable();
            $( "#sortable" ).disableSelection();
        } );

        $( "#sortable" ).sortable({

            update: function( ) {
                var l_ar = [];
                $( ".shortable_data" ).each(function( index,data ) {
                    l_ar.push($(this).val());
                });

                $.get( "{{route('customPageOrder')}}?vals="+l_ar, function( data ) {
                    $( ".result" ).html( data );
                    VanillaToasts.create({
                        //  title: 'Message Title',
                        text:data.message,
                        backgroundColor: "linear-gradient(135deg, #73a5ff, #5477f5)",
                        type: 'success',
                        timeout: 3000
                    });
                });
            }
        });

    </script>
@endsection
