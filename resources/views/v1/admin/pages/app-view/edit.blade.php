@extends('v1.admin.layout.default')
@section('custom_style')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/dashboard/css/mail-profile.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/dashboard/assets/summernote/summernote.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/dashboard/assets/image-preview/image-preview.css')}}"/>


    <link href="{{asset('assets/dashboard/assets/dz/dropzone.css')}}" type="text/css" rel="stylesheet">
    <link href="{{asset('assets/dashboard/assets/dz/basic.css')}}" type="text/css" rel="stylesheet">


    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet"/>


@endsection
@section('content')
    <!-- title and breadcrumb -->
    <div class="row clearfix">
        <div class="col-sm-6">
            <h2>ویو جدید</h2>
        </div>
        <div class="col-sm-6 breadcrumb-col">
            <ol class="breadcrumb">
                <li><a href="{{route('admin.dashboard')}}">پیشخوان</a></li>
                <li><span><a href="{{route('admin.app-view.index')}}">ویوها</a></span></li>
                <li><span>ویو جدید</span></li>
            </ol>
        </div>
    </div>
    <!-- /End title and breadcrumb -->

    <!-- Basic form -->
    <div class="panel" id="basic">
        <div class="panel-heading b#c6f9ff">
            <i class="fa fa-plus-square-o  sort-hand"></i>ویو جدید
            <div class="pan-btn expand"></div>
        </div>
        <div class="panel-body">
            @include('v1.admin.includes.validation-error')
            <form action="{{route('admin.app-view.update',$app_view->id)}}" method="post" enctype="multipart/form-data">
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
                    <span popover="" data-placement="top" data-trigger="hover"
                          data-content="عنوان ویو که اجباری است و در سایت نمایش داده می شود" data-original-title=""
                          title=""><i class="fa fa-question-circle"></i></span>
                    <input id="title" name="title" value="{{ old('title') ? : $app_view->title}}" tabindex="1"
                           placeholder="عنوان"
                           type="text" class="form-control">
                    @if ($errors->has('title'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <strong>{{$errors->first('title')}}</strong>
                        </div>
                    @endif
                </div>
                <br/>


                <div class="form-group">
                    <span class="field-force"></span>
                    <label for="title_color_code">رنگ عنوان</label>
                    <span popover="" data-placement="top" data-trigger="hover"
                          data-content="" data-original-title=""
                          title=""><i class="fa fa-question-circle"></i></span>
                    <input id="title_color_code" name="title_color_code"
                           value="{{ old('title_color_code') ? : $app_view->title_color_code }}"
                           tabindex="1" placeholder="رنگ عنوان"
                           type="text" class="form-control">
                    @if ($errors->has('title_color_code'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <strong>{{$errors->first('title_color_code')}}</strong>
                        </div>
                    @endif
                </div>
                <br/>


                @if(isset($types) && $types->count() > 0)
                    <div class="form-group">
                        <span class="field-force">*</span>
                        <label for="type">نوع</label>
                        <span popover="" data-placement="top" data-trigger="hover" data-content="نوع ویو"
                              data-original-title="" title=""><i class="fa fa-question-circle"></i></span>
                        <select id="type" name="type" class="form-control" tabindex="2">
                            <option disabled selected>انتخاب</option>
                            @foreach($types as $type)
                                <option data-content="{{$type->type_content}}"
                                        value="{{ $type->id }}" {{ ($app_view->type  == $type->id ? "selected": "") }}>{{ $type->type_title_fa." (".$type->type_title.")" }}</option>
                            @endforeach()
                        </select>
                        @if ($errors->has('type'))
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                            aria-hidden="true">&times;</span></button>
                                <strong>{{$errors->first('type')}}</strong>
                            </div>
                        @endif
                    </div> <br/>
                @endif


                <div class="form-group {{$app_view->appViewType->type_content!="ads" ? "display_none" : ""}}" id="ads_container">
                    <span class="field-force"></span>
                    <label for="ads">اگهی های قابل نمایش در ویو را انتخاب کنید .</label>
                    <span popover="" data-placement="top" data-trigger="hover"
                          data-content="می توانید عنوان اگهی را جست و جو و انتخاب کنید." data-original-title=""
                          title=""><i class="fa fa-question-circle"></i></span>
                    <select id="ads" class="js-example-basic-multiple" style="width: 100%" name="ads[]"
                            multiple="multiple">

                        @foreach($ads as $row)
                            <option value="{{$row->id}}" {{in_array($row->id,$ads_id) ? "selected='selected'" : "" }} >{{$row->title}} </option>
                        @endforeach

                    </select>

                </div>

                <div class="form-group {{$app_view->appViewType->type_content!="adsCategory" ? "display_none" : ""}}" id="category_container">
                    <span class="field-force"></span>
                    <label for="category">دسته بندی های قابل نمایش در ویو را انتخاب کنید .</label>
                    <span popover="" data-placement="top" data-trigger="hover"
                          data-content="می توانید عنوان دسته بندی را جست و جو و انتخاب کنید." data-original-title=""
                          title=""><i class="fa fa-question-circle"></i></span>
                    <select id="category" class="js-example-basic-multiple" style="width: 100%" name="categories[]"
                            multiple="multiple">

                        @foreach($categories as $row)
                            <option value="{{$row->id}}" {{in_array($row->id,$categories_id) ? "selected='selected'" : ""}}>{{$row->title}}</option>
                        @endforeach

                    </select>

                </div>
                <br/>


                <div class="form-group">
                    <span class="field-force">*</span>
                    <label for="status">ویو نمایش داده شود؟</label>
                    <span popover="" data-placement="top" data-trigger="hover"
                          data-content="در صورتی که خیر را انتخاب کنید، ویو برای کاربران نمایش داده نخواهد شد"
                          data-original-title="" title=""><i class="fa fa-question-circle"></i></span>
                    <div class="btn-group" id="status" data-toggle="buttons">
                        <label class="btn btn-default btn-on {{ $app_view->published_at ? 'active' : '' }}">
                            <input type="radio" value="1"
                                   name="published" {{ $app_view->published_at ? 'checked' : '' }}>بله</label>
                        <label class="btn btn-default btn-off {{ !$app_view->published_at ? 'active' : '' }}">
                            <input type="radio" value="0"
                                   name="published" {{ !$app_view->published_at ? 'checked' : '' }}>خیر</label>
                    </div>
                    @if ($errors->has('published'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <strong>{{$errors->first('published')}}</strong>
                        </div>
                    @endif
                </div>
                <br/>

                <div class="form-group">
                    <span class="field-force">*</span>
                    <label for="description">توضیحات</label>
                    <span popover="" data-placement="top" data-trigger="hover"
                          data-content="توضیحات ویو که اجباری است و در سایت نمایش داده می شود" data-original-title=""
                          title=""><i class="fa fa-question-circle"></i></span>
                    <textarea id="description" name="description" rows="3" tabindex="9" placeholder="توضیحات"
                              class="form-control">{{ old('description') ? : $app_view->description }}</textarea>

                    @if ($errors->has('description'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <strong>{{$errors->first('description')}}</strong>
                        </div>
                    @endif
                </div>
                <br/>


                <div class="form-group">
                    <span class="field-force"></span>
                    <label for="description_color_code">رنگ توضیحات</label>
                    <span popover="" data-placement="top" data-trigger="hover"
                          data-content="" data-original-title=""
                          title=""><i class="fa fa-question-circle"></i></span>
                    <input id="description_color_code" name="description_color_code"
                           value="{{ old('description_color_code') ? : $app_view->description_color_code }}"
                           tabindex="1" placeholder="رنگ توضیحات"
                           type="text" class="form-control">
                    @if ($errors->has('description_color_code'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <strong>{{$errors->first('description_color_code')}}</strong>
                        </div>
                    @endif
                </div>
                <br/>

                <div class="form-group">
                    <span class="field-force"></span>
                    <label for="background_color_code">رنگ پس زمینه</label>
                    <span popover="" data-placement="top" data-trigger="hover"
                          data-content="" data-original-title=""
                          title=""><i class="fa fa-question-circle"></i></span>
                    <input id="background_color_code" name="background_color_code"
                           value="{{ old('background_color_code') ? : $app_view->background_color_code }}" tabindex="1"
                           placeholder="رنگ پس زمینه"
                           type="text" class="form-control">
                    @if ($errors->has('background_color_code'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <strong>{{$errors->first('background_color_code')}}</strong>
                        </div>
                    @endif
                </div>
                <br/>


                <div class="form-group">
                    <span class="field-force"></span>
                    <label for="logo">تصویر پس زمینه</label>
                    <span popover="" data-placement="top" data-trigger="hover"
                          data-content=""
                          data-original-title="" title=""><i class="fa fa-question-circle"></i></span>


                    <div id="background_image" class="dropzone">
                    </div>
                </div>


                <div class="form-group">
                    <span class="field-force">*</span>
                    <label for="action">متن دکمه عملیات</label>
                    <span popover="" data-placement="top" data-trigger="hover"
                          data-content="" data-original-title=""
                          title=""><i class="fa fa-question-circle"></i></span>
                    <input id="action" name="action"
                           value="{{ old('action') ? : $app_view->action }}" tabindex="1" placeholder="متن دکمه عملیات"
                           type="text" class="form-control">
                    @if ($errors->has('action'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <strong>{{$errors->first('action')}}</strong>
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <span class="field-force"></span>
                    <label for="action_color_code">رنگ دکمه عملیات</label>
                    <span popover="" data-placement="top" data-trigger="hover"
                          data-content="" data-original-title=""
                          title=""><i class="fa fa-question-circle"></i></span>
                    <input id="action_color_code" name="action_color_code"
                           value="{{ old('action_color_code') ? : $app_view->action_color_code }}" tabindex="1"
                           placeholder="رنگ دکمه عملیات"
                           type="text" class="form-control">
                    @if ($errors->has('action_color_code'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <strong>{{$errors->first('action_color_code')}}</strong>
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <span class="field-force"></span>
                    <label for="order">ترتیب</label>
                    <span popover="" data-placement="top" data-trigger="hover"
                          data-content="" data-original-title=""
                          title=""><i class="fa fa-question-circle"></i></span>
                    <input id="order" name="order"
                           value="{{ old('order') ? : $app_view->order}}" tabindex="1" placeholder="ترتیب"
                           type="text" class="form-control">
                    @if ($errors->has('order'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <strong>{{$errors->first('order')}}</strong>
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <span class="field-force">*</span>
                    <label for="need_space">نیاز به فضا دارد ؟</label>
                    <span popover="" data-placement="top" data-trigger="hover"
                          data-content="در صورتی که بله انتخاب شود ویو با فاصله نمایش داده می شود ."
                          data-original-title="" title=""><i class="fa fa-question-circle"></i></span>
                    <div class="btn-group" id="status" data-toggle="buttons">
                        <label class="btn btn-default btn-on {{ $app_view->need_space ? 'active' : '' }}">
                            <input type="radio" value="1"
                                   name="need_space" {{ $app_view->need_space ? 'checked' : '' }}>بله</label>
                        <label class="btn btn-default btn-off {{ !$app_view->need_space ? 'active' : '' }}">
                            <input type="radio" value="0"
                                   name="need_space" {{ !$app_view->need_space ? 'checked' : '' }}>خیر</label>
                    </div>
                    @if ($errors->has('need_space'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <strong>{{$errors->first('need_space')}}</strong>
                        </div>
                    @endif
                </div>
                <br/>


                <div class="panel-body">
                    @include('v1.admin.includes.page-table-buttons', ['register'=>true, 'cancel' => ['route' => 'admin.app-view.index']])
                </div>
            </form>
        </div>
    </div>

    <!-- /End Basic form -->
@endsection
@section('custom_script')

    <script src="//cdn.ckeditor.com/4.8.0/full/ckeditor.js"></script>
    <script type="text/javascript" src="{{asset('assets/dashboard/js/mailbox.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/dashboard/assets/summernote/summernote.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/dashboard/assets/dz/dropzone.js')}}"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

    <script>

        $('#type').change(function () {

            if ($('option:selected', this).data('content') == "ads") {

                $('#ads_container').slideDown();
                $('#category_container').slideUp();
            } else {
                $('#category_container').slideDown();
                $('#ads_container').slideUp();
            }

        });

    </script>

    <script>
        $('.js-example-basic-multiple').select2({
            placeholder: "یک یا چند مورد را انتحاب کنید",
            allowClear: true
        });

    </script>

    <script>

        Dropzone.autoDiscover = false;
        // or disable for specific dropzone:
        // Dropzone.options.myDropzone = false;


        $(function () {
            // Now that the DOM is fully loaded, create the dropzone, and setup the
            // event listeners
            var myDropzone = new Dropzone("div#background_image", {url: "{{route('admin.upload_image')}}"});

            myDropzone.on("sending", function (file, xhr, formData) {
                formData.append("_token", "{{ csrf_token() }}");
                formData.append("model", "homeBackgroundImage");
                formData.append("type", "image");

            });

            myDropzone.on("success", function (file, response) {

                let c = $('#image_id_div').html();

                c = c + "<input type='hidden' name='background_image_id[]' value=" + response + ">";

                $('#image_id_div').html(c);
            });
        });


    </script>

    <script>
        $(document).ready(function () {


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
            switch (type) {
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
