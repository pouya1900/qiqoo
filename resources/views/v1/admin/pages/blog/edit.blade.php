@extends('v1.admin.layout.default')
@section('custom_style')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/dashboard/css/mail-profile.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/dashboard/assets/summernote/summernote.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/dashboard/assets/image-preview/image-preview.css')}}"/>

    <link href="{{asset('assets/dashboard/assets/dz/dropzone.css')}}" type="text/css" rel="stylesheet">
    <link href="{{asset('assets/dashboard/assets/dz/basic.css')}}" type="text/css" rel="stylesheet">

@endsection
@section('content')

    <!-- title and breadcrumb -->
    <div class="row clearfix">
        <div class="col-sm-6">
            <h2>ویرایش اخبار</h2>
        </div>
        <div class="col-sm-6 breadcrumb-col">
            <ol class="breadcrumb">
                <li><a href="{{route('admin.dashboard')}}">پیشخوان</a></li>
                <li><span><a href="{{route('admin.blog.index')}}">اخبار</a></span></li>
                <li><span>ویرایش</span></li>
            </ol>
        </div>
    </div>
    <!-- /End title and breadcrumb -->

    <!-- Basic form -->
    <div class="panel" id="basic">
        <div class="panel-heading b#c6f9ff">
            <i class="fa fa-pencil-square-o sort-hand"></i>{{ $blog->shortTitle }}
            <div class="pan-btn expand"></div>
        </div>
        <div class="panel-body">@include('v1.admin.includes.validation-error')
            <form action="{{route('admin.blog.update', $blog->id)}}" method="post" enctype="multipart/form-data">
                {{ method_field('put') }}
                {{ csrf_field() }}

                <div id="image_id_div"></div>


            @if(count($errors))
                    <div class="alert alert-danger">
                        <p>لطفا قبل از ادامه ی کار خطاهای زیر را اصلاح کنید:</p>
                        @foreach($errors->all() as $error)
                            <p class="danger">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                <div class="form-group">
                    <span class="field-force">*</span>
                    <label for="title">عنوان</label>
                    <span popover="" data-placement="top" data-trigger="hover"
                          data-content="عنوان خبر که اجباری است و در سایت نمایش داده می شود" data-original-title=""
                          title=""><i class="fa fa-question-circle"></i></span>
                    <input id="title" name="title" value="{{ old('title') ?: $blog->title }}" tabindex="1"
                           placeholder="عنوان" type="text" class="form-control">
                    @if ($errors->has('title'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <strong>{{$errors->first('title')}}</strong>
                        </div>
                    @endif
                </div>
                <br/>

                @if(isset($categories) && $categories->count() > 0)
                    <div class="form-group">
                        <span class="field-force">*</span>
                        <label for="category_id">دسته بندی</label>
                        <span popover="" data-placement="top" data-trigger="hover" data-content="دسته بندی خبر"
                              data-original-title="" title=""><i class="fa fa-question-circle"></i></span>
                        <select id="category_id" name="category_id" class="form-control" tabindex="2">
                            <option disabled selected>انتخاب</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ ($blog->category_id  == $cat->id ? "selected": "") }}>{{ $cat->shortTitle }}</option>
                            @endforeach()
                        </select>
                        @if ($errors->has('category_id'))
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                            aria-hidden="true">&times;</span></button>
                                <strong>{{$errors->first('category_id')}}</strong>
                            </div>
                        @endif
                    </div> <br/>
                @endif

                <div class="form-group">
                    <span class="field-force">*</span>
                    <label for="status">خبر منتشر شود؟</label>
                    <span popover="" data-placement="top" data-trigger="hover"
                          data-content="در صورتی که خیر را انتخاب کنید، خبر برای کاربران نمایش داده نخواهد شد"
                          data-original-title="" title=""><i class="fa fa-question-circle"></i></span>
                    <div class="btn-group" id="status" data-toggle="buttons">
                        <label class="btn btn-default btn-on {{ !empty($blog->published_at) ? 'active' : '' }}">
                            <input type="radio" value="1"
                                   name="published" {{ !empty($blog->published_at) ? 'checked' : '' }}>بله</label>
                        <label class="btn btn-default btn-off {{ empty($blog->published_at) ? 'active' : '' }}">
                            <input type="radio" value="0"
                                   name="published" {{ empty($blog->published) ? 'checked' : '' }}>خیر</label>
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
                    <label for="status">خبر در اسلایدر نمایش داده شود ؟</label>
                    <span popover="" data-placement="top" data-trigger="hover"
                          data-content="در صورتی که خیر را انتخاب کنید، خبر در اسلایدر نمایش داده نخواهد شد."
                          data-original-title="" title=""><i class="fa fa-question-circle"></i></span>
                    <div class="btn-group" id="status" data-toggle="buttons">

                        <label class="btn btn-default btn-on {{ $blog->slider_blog ? 'active' : '' }}">
                            <input type="radio" value="1" name="slider_blog" {{ $blog->slider_blog ? 'checked' : '' }}>بله</label>
                        <label class="btn btn-default btn-off {{ !$blog->slider_blog ? 'active' : '' }}">
                            <input type="radio" value="0" name="slider_blog" {{ !$blog->slider_blog ? 'checked' : '' }}>خیر</label>
                    </div>
                    @if ($errors->has('slider_blog'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <strong>{{$errors->first('slider_blog')}}</strong>
                        </div>
                    @endif
                </div>
                <br/>


                <div class="form-group">
                    <span class="field-force"></span>
                    <label for="logo">تصویر اخبار</label>
                    <span popover="" data-placement="top" data-trigger="hover"
                          data-content="حداکثر اندازه تصویر 900*450 می باشد که به سه تصویر تبدیل می شود. در صفحات سایت استفاده خواهد شد."
                          data-original-title="" title=""><i class="fa fa-question-circle"></i></span>


                    <div  id="content_image" class="dropzone">
                    </div>

                    @if ($errors->has('logo'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <strong>{{$errors->first('logo')}}</strong>
                        </div>
                    @endif
                </div>


                <div class="form-group">
                    <span class="field-force">*</span>
                    <label for="status">خبر از نوع ویدیو است ؟</label>
                    <span popover="" data-placement="top" data-trigger="hover"
                          data-content="در صورتی که خبر را از نوع ویدیو انتخاب کنید باید برای ان بک ویدیو اپلود کنید، "
                          data-original-title="" title=""><i class="fa fa-question-circle"></i></span>
                    <div class="btn-group" id="status" data-toggle="buttons">
                        <label class="btn btn-default btn-on {{$blog->is_video ? 'active' : '' }}">
                            <input type="radio" value="1"
                                   name="is_video" {{ $blog->is_video ? 'checked' : ''  }}>بله</label>
                        <label class="btn btn-default btn-off {{!$blog->is_video ? 'active' : '' }}">
                            <input type="radio" value="0"
                                   name="is_video" {{!$blog->is_video ? 'checked' : ''  }}>خیر</label>
                    </div>
                    @if ($errors->has('is_video'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <strong>{{$errors->first('is_video')}}</strong>
                        </div>
                    @endif
                </div>
                <br/>


{{--                 manual duration set , will be delete in developed app--}}

                <div class="form-group">
                    <span class="field-force"></span>
                    <label for="video_manual_duration">مدت ویدیو</label>
                    <span popover="" data-placement="top" data-trigger="hover"
                          data-content="مدت زمان ویدیو به ثانیه ، حتما قبل اپلود ویدیو مدت را وارد کنید ."
                          data-original-title="" title=""><i class="fa fa-question-circle"></i></span>

                    <input type="text" name="video_manual_duration" id="video_manual_duration">
                </div>

{{--                 manual duration set , will be delete in developed app--}}


                <div class="form-group">
                    <span class="field-force"></span>
                    <label for="content_video">ویدیو اخبار</label>
                    <span popover="" data-placement="top" data-trigger="hover"
                          data-content=""
                          data-original-title="" title=""><i class="fa fa-question-circle"></i></span>


                    <div  id="content_video" class="dropzone">
                    </div>

                    @if ($errors->has('logo'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <strong>{{$errors->first('logo')}}</strong>
                        </div>
                    @endif
                </div>



                <div class="form-group">
                    <label for="meta_title">تگ متای عنوان (title)</label>
                    <span popover="" data-placement="top" data-trigger="hover"
                          data-content="تگ عنوان جهت بهبود seo سامانه استفاده می شود." data-original-title=""
                          title=""><i class="fa fa-question-circle"></i></span>
                    <input id="meta_title" name="meta_title" value="{{ old('meta_title') ?: $blog->meta_title }}"
                           tabindex="4" placeholder="عنوان" type="text" class="form-control">
                    @if ($errors->has('meta_title'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <strong>{{$errors->first('meta_title')}}</strong>
                        </div>
                    @endif
                </div>
                <div class="form-group">
                    <label for="meta_author">تگ متای نویسنده (author)</label>
                    <span popover="" data-placement="top" data-trigger="hover"
                          data-content="تگ نویسنده جهت بهبود seo سامانه استفاده می شود و در صورت خالی بودن برابر با مقدار پیشفرض می باشد"
                          data-original-title="" title=""><i class="fa fa-question-circle"></i></span>
                    <input id="meta_author" name="meta_author" value="{{ old('meta_author') ?: $blog->meta_author }}"
                           tabindex="5" placeholder="نویسنده" type="text" class="form-control">
                    @if ($errors->has('meta_author'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <strong>{{$errors->first('meta_author')}}</strong>
                        </div>
                    @endif
                </div>
                <div class="form-group">
                    <span class="field-force">*</span>
                    <label for="meta_keywords">تگ متای کلمات کلیدی (keywords)</label>
                    <span popover="" data-placement="top" data-trigger="hover"
                          data-content="تگ کلمات کلیدی جهت بهبود seo سامانه استفاده می شود و در صورت خالی بودن برابر با مقدار پیشفرض می باشد. جهت جداسازی کلمات از کاما {,}استفاده شود"
                          data-original-title="" title=""><i class="fa fa-question-circle"></i></span>
                    <input id="meta_keywords" name="meta_keywords"
                           value="{{ old('meta_keywords') ?: $blog->meta_keywords}}" tabindex="6"
                           placeholder="کلمات کلیدی" type="text" class="form-control">
                    @if ($errors->has('meta_keywords'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <strong>{{$errors->first('meta_keywords')}}</strong>
                        </div>
                    @endif
                </div>
                <div class="form-group">
                    <label for="meta_description">تگ متای توضیحات (description)</label>
                    <span popover="" data-placement="top" data-trigger="hover"
                          data-content="تگ توضیحات جهت بهبود seo سامانه استفاده می شود و در صورت خالی بودن برابر با مقدار پیشفرض می باشد."
                          data-original-title="" title=""><i class="fa fa-question-circle"></i></span>
                    <textarea rows="3" id="meta_description" name="meta_description" tabindex="7" placeholder="توضیحات"
                              class="form-control">{{ old('meta_description') ?: $blog->meta_description}}</textarea>
                    @if ($errors->has('meta_description'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <strong>{{$errors->first('meta_description')}}</strong>
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <span class="field-force">*</span>
                    <label for="short_description">توضیح کوتاه</label>
                    <textarea tabindex="8" rows="3" id="short_description" name="short_description" tabindex="7"
                              placeholder="توضیح کوتاه"
                              class="form-control">{{ old('short_description') ?: $blog->short_description }}</textarea>
                    @if ($errors->has('short_description'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <strong>{{$errors->first('short_description')}}</strong>
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <span class="field-force">*</span>
                    <label for="blog_text">محتوای اصلی</label>
                    <textarea id="blog_text" name="text" tabindex="9">{{old('text') ?: $blog->text}}</textarea>
                    @if ($errors->has('text'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <strong>{{$errors->first('text')}}</strong>
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="blog_en_text">محتوای اصلی لاتین</label>
                    <textarea id="blog_en_text" name="en_text"
                              tabindex="9">{{ old('en_text') ?: $blog->en_text }}</textarea>
                    @if ($errors->has('en_text'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <strong>{{$errors->first('en_text')}}</strong>
                        </div>
                    @endif
                </div>

                <div class="panel-body">
                    @include('v1.admin.includes.page-table-buttons', ['register'=>true, 'cancel' => ['route' => 'admin.blog.index']])
                </div>
            </form>
        </div>
    </div>

    <!-- /End Basic form -->
@endsection
@section('custom_script')
    <script src="//cdn.ckeditor.com/4.8.0/full/ckeditor.js"></script>
    <script type="text/javascript" src="{{asset('assets/dashboard/assets/dz/dropzone.js')}}"></script>


    <script>

        Dropzone.autoDiscover = false;
        // or disable for specific dropzone:
        // Dropzone.options.myDropzone = false;



        $(function () {
            // Now that the DOM is fully loaded, create the dropzone, and setup the
            // event listeners
            var myDropzone = new Dropzone("div#content_image", { url: "{{route('admin.upload_image')}}"});

            myDropzone.on("sending", function (file, xhr,formData) {
                formData.append("_token", "{{ csrf_token() }}");
                formData.append("model", "blogContent");
                formData.append("type", "image");

            });

            myDropzone.on("success", function (file, response) {

                let c=$('#image_id_div').html();

                c=c+"<input type='hidden' name='content_image_id[]' value="+response+">";

                $('#image_id_div').html(c);
            });
        });

        $(function () {
            // Now that the DOM is fully loaded, create the dropzone, and setup the
            // event listeners
            var myDropzone2 = new Dropzone("div#content_video", { url: "{{route('admin.upload_image')}}"});

            myDropzone2.on("sending", function (file, xhr,formData) {
                formData.append("_token", "{{ csrf_token() }}");
                formData.append("model", "blogContentVideo");
                formData.append("type", "video");

                // manual duration set , will be delete in developed app

                let duration=parseFloat($('#video_manual_duration').val())*1000;

                formData.append("duration", duration);


                // manual duration set , will be delete in developed app

            });

            myDropzone2.on("success", function (file, response) {

                let c=$('#image_id_div').html();

                c=c+"<input type='hidden' name='content_video_id[]' value="+response+">";

                $('#image_id_div').html(c);
            });
        });


    </script>


    <script>
        CKEDITOR.replace('blog_text', {
            contentsLangDirection: 'rtl',
            // Define the toolbar groups as it is a more accessible solution.
            toolbarGroups: [{
                "name": "basicstyles",
                "groups": ["basicstyles"]
            },
                {
                    "name": "links",
                    "groups": ["links"]
                },
                {
                    "name": "paragraph",
                    "groups": ["align", "list", "blocks"]
                },
                {
                    "name": "document",
                    "groups": ["mode"]
                },
                {
                    "name": "insert",
                    "groups": ["insert"]
                },
                {
                    "name": "styles",
                    "groups": ["styles"]
                }
            ],
            // Remove the redundant buttons from toolbar groups defined above.
            removeButtons: 'Underline,Strike,Subscript,Superscript,Anchor,Styles,Specialchar,Font,FontSize|Find,SelectAll,Scayt,Source,Save,Templates,NewPage,Preview,Print,About,Flash,Table,HorizontalRule,SpecialChar,PageBreak,Iframe,FontSize,Outdent,Indent,RemoveFormat,CopyFormatting,Strike,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Cut,Copy,Paste,PasteText,PasteFromWord'
        });

        var editor2 = CKEDITOR.replace('blog_en_text', {
            // Define the toolbar groups as it is a more accessible solution.
            toolbarGroups: [{
                "name": "basicstyles",
                "groups": ["basicstyles"]
            },
                {
                    "name": "links",
                    "groups": ["links"]
                },
                {
                    "name": "paragraph",
                    "groups": ["align", "list", "blocks"]
                },
                {
                    "name": "document",
                    "groups": ["mode"]
                },
                {
                    "name": "insert",
                    "groups": ["insert"]
                },
                {
                    "name": "styles",
                    "groups": ["styles"]
                }
            ],
            // Remove the redundant buttons from toolbar groups defined above.
            removeButtons: 'Underline,Strike,Subscript,Superscript,Anchor,Styles,Specialchar,Font,FontSize|Find,SelectAll,Scayt,Source,Save,Templates,NewPage,Preview,Print,About,Flash,Table,HorizontalRule,SpecialChar,PageBreak,Iframe,FontSize,Outdent,Indent,RemoveFormat,CopyFormatting,Strike,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Cut,Copy,Paste,PasteText,PasteFromWord'
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
