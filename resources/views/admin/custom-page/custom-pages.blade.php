@extends('admin.master',['menu'=>'setting', 'sub_menu'=>'custom_pages'])
@section('title',isset($cp) ? 'Update Custom Page' : 'Add Custom Page')
@section('style')
    <style>
        .ck.ck-editor__main>.ck-editor__editable {
            height: 180px;
        }
    </style>
@endsection
@section('content')
    <div class="custom-breadcrumb">
        <div class="row">
            <div class="col-12">
                <ul>
                    <li>{{__('Custom Pages')}}</li>
                    <li class="active-item">{{$title}}</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="user-management add-custom-page">
        <div class="row">
            <div class="col-12">
                <div class="header-bar">
                    <div class="table-title">
                        <h3>{{$title}}</h3>
                    </div>
                </div>
                <div class="profile-info-form">
                    <form action="{{route('adminCustomPageSave')}}" method="post">
                        @if(!empty($cp->id))
                            <input type="hidden" name="edit_id" value="{{$cp->id}}">
                        @endif
                        @csrf
                        <div class="row">
                            <div class="col-xl-12 mb-xl-0 mb-4">
                                <div class="form-group">
                                    <label>{{__('Menu name')}}</label>
                                    <input type="text" class="form-control" name="menu" placeholder="{{__('Menu name')}}" @if(isset($cp))value="{{$cp->key}}" @else value="{{old('key')}}" @endif>
                                </div>
                                <div class="form-group">
                                    <label>{{__('Page Title')}}</label>
                                    <input type="text" class="form-control" name="title" placeholder="{{__('Title')}}" @if(isset($cp))value="{{$cp->title}}" @else value="{{old('title')}}" @endif>
                                </div>
                                <div class="form-group">
                                    <label for="">{{__('Description')}}</label>
                                    <textarea rows="10" name="description" id="editor" class="textarea form-control" >@if(isset($cp)){!! $cp->description !!} @else {{old('description')}} @endif</textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <button type="submit" class="btn add-faq-btn">
                                        @if(isset($cp)) {{__('Update')}} @else {{__('Submit')}} @endif
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- .user-area end -->
@endsection
@section('script')
    <script src="{{asset('assets/admin/js/ckeditor.js')}}"></script>
    <script>
        ClassicEditor
            .create( document.querySelector( '#editor',function () {
                config.width = 500;
            } ) )
            .catch( error => {
                console.error( error );
            } );
    </script>
    <script>
        CKEDITOR.replace( '#editor', {
            uiColor: '#14B8C4',
            toolbar: [
                [ 'Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink' ],
                [ 'FontSize', 'TextColor', 'BGColor' ]
            ],
            width:['350px']

        });

    </script>


@endsection
