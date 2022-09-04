@extends('v1.admin.layout.default')
@section('custom_style')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/dashboard/css/mail-profile.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/dashboard/assets/summernote/summernote.css')}}" />

    <link href="{{asset('assets/dashboard/assets/dz/dropzone.css')}}" type="text/css" rel="stylesheet">
    <link href="{{asset('assets/dashboard/assets/dz/basic.css')}}" type="text/css" rel="stylesheet">


@endsection
@section('content')
    <!-- tiitle and breadcrumb -->

    <!-- title and breadcrumb -->
    <div class="row clearfix">
        <div class="col-sm-6">
            <h2>ویرایش دسته بندی اخبار</h2>
        </div>
        <div class="col-sm-6 breadcrumb-col">
            <ol class="breadcrumb">
                <li><a href="{{route('admin.dashboard')}}">پیشخوان</a></li>
                <li><span>مدیریت محتوا</span></li>
                <li><span><a href="{{route('admin.blog-category.index')}}">دسته بندی اخبار</a></span></li>
                <li><span>ویرایش دسته بندی اخبار</span></li>
            </ol>
        </div>
    </div>
    <!-- /End title and breadcrumb -->

    <!-- Basic form -->
    <div class="panel" id="basic">
        <div class="panel-heading b#c6f9ff">
            <i class="fa fa-pencil-square-o sort-hand"></i>{{ $category->shortTitle }}
            <div class="pan-btn expand"></div>
        </div>

        <div class="panel-body">
            @include('v1.admin.includes.validation-error')
            <form action="{{route('admin.blog-category.update', $category->id)}}" method="post" enctype="multipart/form-data">
                {{ method_field('put') }}
                {{ csrf_field() }}
                <div id="image_id_div"></div>

                <div class="form-group">
                    <span class="field-force">*</span>
                    <label for="title">عنوان</label>
                    <input id="title" name="title" value="{{ old('title') ?? $category->title }}" tabindex="1" placeholder="نام" type="text" class="form-control">
                    @if ($errors->has('title'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>{{$errors->first('title')}}</strong>
                        </div>
                    @endif
                </div><br/>

                <div class="form-group">
                    <span class="field-force">*</span>
                    <label for="parent_id">والد</label>
                    <select id="parent_id" name="parent_id" class="form-control" tabindex="2">
                        <option value="" selected >ریشه</option>
                        @foreach($allCategories as $cat)
                            <option value="{{ $cat->id }}" {{ ($category->parent_id  == $cat->id ? "selected": "") }}>{{ $cat->shortTitle }}</option>
                        @endforeach()
                    </select>
                    @if ($errors->has('parent_id'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>{{$errors->first('parent_id')}}</strong>
                        </div>
                    @endif
                </div> <br/>

                <div class="form-group">
                    <label for=color_code>کد رنگ</label>
                    <textarea id=color_code name=color_code tabindex="4" placeholder="کد رنگ دسته بندی (اختیاری)" class="form-control">{{ old('color_code') ?? $category->color_code }}</textarea>
                    @if ($errors->has('color_code'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>{{$errors->first('color_code')}}</strong>
                        </div>
                    @endif
                </div><br/>

                <div class="form-group">
                    <span class="field-force">*</span>
                    <label for="status">انتخاب بعنوان دسته بندی برگزیده؟</label>
                    <span popover=""  data-placement="top" data-trigger="hover" data-content="در صورتی که بعنوان دسته بندی برگزیده انتخاب شود، در اپ نمایش داده خواهد شد" data-original-title="" title=""><i class="fa fa-question-circle"></i></span>
                    <div class="btn-group" id="status" data-toggle="buttons">
                        <label class="btn btn-default btn-on {{ $category->is_favorite ? 'active' : '' }}">
                            <input type="radio" value="1" name="is_favorite" {{ $category->is_favorite ? 'checked' : '' }}>بله</label>
                        <label class="btn btn-default btn-off {{ !$category->is_favorite ? 'active' : '' }}">
                            <input type="radio" value="0" name="is_favorite" {{ !$category->is_favorite ? 'checked' : '' }}>خیر</label>
                    </div>
                    @if ($errors->has('is_favorite'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>{{$errors->first('is_favorite')}}</strong>
                        </div>
                    @endif
                </div><br/>

                <div class="form-group">
                    <label for=order>ترتیب</label>
                    <textarea id=order name=order tabindex="3" placeholder="ترتیب نمایش دسته بندی در اپ(اختیاری)" class="form-control">{{ old('order') ?? $category->order }}</textarea>
                    @if ($errors->has('order'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>{{$errors->first('order')}}</strong>
                        </div>
                    @endif
                </div><br/>

                <div class="form-group">
                    <label for="icon">تصویر دسته بندی</label>
                    <span popover=""  data-placement="top" data-trigger="hover" data-content="آیکن مربوط به دسته بندی اخبار" data-original-title="" title=""><i class="fa fa-question-circle"></i></span>

                    <div  id="icon_image" class="dropzone">
                    </div>

                    @if ($errors->has('icon'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>{{$errors->first('icon')}}</strong>
                        </div>
                    @endif
                </div>

                @include('v1.admin.includes.page-table-buttons', ['register'=>TRUE, 'cancel' => ['route' => 'admin.blog-category.index']])
            </form>
        </div>
    </div>

    <!-- /End Basic form -->
@endsection
@section('custom_script')
    <script type="text/javascript" src="{{asset('assets/dashboard/js/mailbox.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/dashboard/assets/summernote/summernote.js')}}"></script>


    <script type="text/javascript" src="{{asset('assets/dashboard/assets/dz/dropzone.js')}}"></script>

    <script>

        Dropzone.autoDiscover = false;
        // or disable for specific dropzone:
        // Dropzone.options.myDropzone = false;



        $(function () {
            // Now that the DOM is fully loaded, create the dropzone, and setup the
            // event listeners
            var myDropzone = new Dropzone("div#icon_image", { url: "{{route('admin.upload_image')}}" , maxFiles:1});

            myDropzone.on("sending", function (file, xhr,formData) {
                formData.append("_token", "{{ csrf_token() }}");
                formData.append("model", "blogCategory");
                formData.append("type", "logo");

            });

            myDropzone.on("success", function (file, response) {

                let c=$('#image_id_div').html();

                c=c+"<input type='hidden' name='icon_id[]' value="+response+">";

                $('#image_id_div').html(c);
            });
        });



    </script>



    <script>
        $(document).ready(function() {
            @if(session()->has('notifications.message'))
            $('#toast-container').remove();
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-top-full-width",
                "preventDuplicates": true,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "3000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
            var type = "{{ session()->get('notifications.alert_type', 'info') }}";
            switch(type){
                case 'info':
                    toastr.info("{{ session()->get('notifications.message') }}");
                    break;

                case 'warning':
                    toastr.warning("{{ session()->get('notifications.message') }}");
                    break;

                case 'success':
                    toastr.success("{{ session()->get('notifications.message') }}");
                    break;

                case 'error':
                    toastr.error("{{ session()->get('notifications.message') }}");
                    break;
            }
            {{session()->forget('notifications')}}
            @endif
        });
    </script>
@endsection
