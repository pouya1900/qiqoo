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
            <h2>ویرایش دسته بندی آگهی</h2>
        </div>
        <div class="col-sm-6 breadcrumb-col">
            <ol class="breadcrumb">
                <li><a href="{{route('admin.dashboard')}}">پیشخوان</a></li>
                <li><span>آگهی ها</span></li>
                <li><span><a href="{{route('admin.category.index')}}">دسته بندی ها</a></span></li>
                <li><span>ویرایش دسته بندی آگهی ها</span></li>
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
            <form action="{{route('admin.category.update', $category->id)}}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ method_field('put') }}

                <div id="image_id_div"></div>


            @if(count($errors))
                    <div class="alert alert-danger alert-dismissible">
                        <p>لطفا قبل از ادامه ی کار خطاهای زیر را اصلاح کنید:</p>
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                <div class="form-group">
                    <span class="field-force">*</span>
                    <label for="title">عنوان</label>
                    <span popover=""  data-placement="top" data-trigger="hover" data-content="عنوان دسته بندی که اجباری است و در سایت نمایش داده می شود" data-original-title="" title=""><i class="fa fa-question-circle"></i></span>
                    <input id="title" name="title" value="{{ old('title') ?? $category->title }}" tabindex="1" placeholder="عنوان" type="text" class="form-control">
                    @if ($errors->has('title'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>{{$errors->first('title')}}</strong>
                        </div>
                    @endif
                </div><br/>

                @if(count($allCategories))
                    <div class="form-group">
                        <span class="field-force">*</span>
                        <label for="parent_id">والد</label>
                        <span popover=""  data-placement="top" data-trigger="hover" data-content="اگر این دسته بندی زیردسته دسته بندی دیگری است، در این قسمت انتخاب کنید" data-original-title="" title=""><i class="fa fa-question-circle"></i></span>
                        <select id="parent_id" name="parent_id" class="form-control" tabindex="2">
                            <option selected value="">ریشه</option>
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
                    </div><br>
                @endif

                <div class="form-group">
                    <label for="description">توضیحات</label>
                    <textarea id="description" name="description" tabindex="2" placeholder="توضیحات دسته بندی (اختیاری)" class="form-control">{{ old('description')  ?? $category->description}}</textarea>
                    @if ($errors->has('description'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>{{$errors->first('description')}}</strong>
                        </div>
                    @endif
                </div><br/>

                <div class="form-group">
                    <label for="price">قیمت</label>
                    <span popover=""  data-placement="top" data-trigger="hover" data-content="در صورتی که قراردادن آگهی در این دسته رایگان است، مقدار 0 را وارد نمایید" data-original-title="" title=""><i class="fa fa-question-circle"></i></span>
                    <input id="price" name="price" value="{{ old('price')  ?? $category->price }}" tabindex="3" placeholder="قیمت (رایگان = 0)" type="number" class="form-control">
                    @if ($errors->has('price'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>{{$errors->first('price')}}</strong>
                        </div>
                    @endif
                </div><br/>

                <div class="form-group">
                    <label for="price">ترتیب</label>
                    <span popover=""  data-placement="top" data-trigger="hover" data-content="ترتیب قرارگیری دسته بندی ها در صفحه اصلی و صفحه دسته بندی ها" data-original-title="" title=""><i class="fa fa-question-circle"></i></span>
                    <input id="order" name="order" value="{{ old('order')  ?? $category->order }}" tabindex="3" placeholder="ترتیب دسته" type="number" class="form-control">
                    @if ($errors->has('order'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>{{$errors->first('order')}}</strong>
                        </div>
                    @endif
                </div><br/>

                <div class="form-group">
                    <span class="field-force">*</span>
                    <label for="status">دسته بندی محبوب؟</label>
                    <div class="btn-group" id="status" data-toggle="buttons">
                        <label class="btn btn-default btn-on {{ $category->is_favorite ? 'active' : '' }}">
                            <input type="radio" value="1" name="is_favorite" {{ $category->is_favorite ? 'checked' : '' }}>بله</label>
                        <label class="btn btn-default btn-off {{ $category->is_favorite == 0 ? 'active' : '' }}">
                            <input type="radio" value="0" name="is_favorite" {{ $category->is_favorite == 0 ? 'checked' : '' }}>خیر</label>
                    </div>
                    @if ($errors->has('is_favorite'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>{{$errors->first('is_favorite')}}</strong>
                        </div>
                    @endif
                </div><br/>

                <div class="form-group">
                    <span class="field-force">*</span>
                    <label for="status">نمایش در صفحه اصلی؟</label>
                    <span popover=""  data-placement="top" data-trigger="hover" data-content="در صورتی که این گزینه را انتخاب کنید، این دسته بندی در صفحه اصلی نمایش داده خواهد شد" data-original-title="" title=""><i class="fa fa-question-circle"></i></span>
                    <div class="btn-group" id="status" data-toggle="buttons">
                        <label class="btn btn-default btn-on {{ $category->show_in_home ? 'active' : '' }}">
                            <input type="radio" value="1" name="show_in_home" {{ $category->show_in_home ? 'checked' : '' }}>بله</label>
                        <label class="btn btn-default btn-off {{ $category->show_in_home == 0 ? 'active' : '' }}">
                            <input type="radio" value="0" name="show_in_home" {{ $category->show_in_home == 0 ? 'checked' : '' }}>خیر</label>
                    </div>
                    @if ($errors->has('show_in_home'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>{{$errors->first('show_in_home')}}</strong>
                        </div>
                    @endif
                </div><br/>

                <div class="form-group">
                    <span class="field-force">*</span>
                    <label for="status">نمایش در صفحه دسته بندی های آگهی؟</label>
                    <span popover=""  data-placement="top" data-trigger="hover" data-content="در صورتی که این گزینه را انتخاب کنید، این دسته بندی در قسمت صفحه دسته بندی های آگهی نمایش داده خواهد شد" data-original-title="" title=""><i class="fa fa-question-circle"></i></span>
                    <div class="btn-group" id="status" data-toggle="buttons">
                        <label class="btn btn-default btn-on {{ $category->show_in_categories ? 'active' : '' }}">
                            <input type="radio" value="1" name="show_in_categories" {{ $category->show_in_categories ? 'checked' : '' }}>بله</label>
                        <label class="btn btn-default btn-off {{ $category->show_in_categories == 0 ? 'active' : '' }}">
                            <input type="radio" value="0" name="show_in_categories" {{ $category->show_in_categories == 0 ? 'checked' : '' }}>خیر</label>
                    </div>
                    @if ($errors->has('show_in_categories'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>{{$errors->first('show_in_categories')}}</strong>
                        </div>
                    @endif
                </div><br/>



                <div class="form-group">
                    <span class="field-force">*</span>
                    <label for="logo">آیکن دسته بندی</label>
                    <span popover=""  data-placement="top" data-trigger="hover" data-content="آیکن دسته بندی یک تصویر کوچک 34*34 است که در صفحات سایت نمایش داده خواهد شد" data-original-title="" title=""><i class="fa fa-question-circle"></i></span>
                    <div  id="icon" class="dropzone">
                    </div>
                    @if ($errors->has('icon'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>{{$errors->first('icon')}}</strong>
                        </div>
                    @endif
                </div>


                <div class="form-group">
                    <span class="field-force">*</span>
                    <label for="logo">تصویر دسته بندی</label>
                    <span popover=""  data-placement="top" data-trigger="hover" data-content="یک تصویر 350*280 که در صفحه اصلی و صفحات دسته بندی نمایش داده خواهد شد" data-original-title="" title=""><i class="fa fa-question-circle"></i></span>
                    <div  id="logo" class="dropzone">
                    </div>
                    @if ($errors->has('logo'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>{{$errors->first('logo')}}</strong>
                        </div>
                    @endif
                </div>










                <div class="panel-body">
                    @include('v1.admin.includes.page-table-buttons', ['register'=>TRUE, 'cancel' => ['route' => 'admin.category.index']])
                </div>
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
            var myDropzone = new Dropzone("div#icon", { url: "{{route('admin.upload_image')}}" , maxFiles:1});

            myDropzone.on("sending", function (file, xhr,formData) {
                formData.append("_token", "{{ csrf_token() }}");
                formData.append("model", "adsCategoryIcon");
                formData.append("type", "logo");

            });

            myDropzone.on("success", function (file, response) {

                let c=$('#image_id_div').html();

                c=c+"<input type='hidden' name='icon_id[]' value="+response+">";

                $('#image_id_div').html(c);
            });
        });

        $(function () {
            // Now that the DOM is fully loaded, create the dropzone, and setup the
            // event listeners
            var myDropzone = new Dropzone("div#logo", { url: "{{route('admin.upload_image')}}" , maxFiles:1});

            myDropzone.on("sending", function (file, xhr,formData) {
                formData.append("_token", "{{ csrf_token() }}");
                formData.append("model", "adsCategoryLogo");
                formData.append("type", "logo");

            });

            myDropzone.on("success", function (file, response) {

                let c=$('#image_id_div').html();

                c=c+"<input type='hidden' name='logo_id[]' value="+response+">";

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
