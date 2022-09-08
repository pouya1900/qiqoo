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
            <h2>ویرایش شهر</h2>
        </div>
        <div class="col-sm-6 breadcrumb-col">
            <ol class="breadcrumb">
                <li><a href="{{route('admin.dashboard')}}">پیشخوان</a></li>
                <li><span><a href="{{route('admin.city.index')}}">شهر ها</a></span></li>
                <li><span>ویرایش</span></li>
            </ol>
        </div>
    </div>
    <!-- /End title and breadcrumb -->

    <!-- Basic form -->
    <div class="panel" id="basic">
        <div class="panel-heading b#c6f9ff">
            <i class="fa fa-pencil-square-o sort-hand"></i>{{ $city->title }}
            <div class="pan-btn expand"></div>
        </div>
        <div class="panel-body">@include('v1.admin.includes.validation-error')
            <form action="{{route('admin.city.update', $city->id)}}" method="post" enctype="multipart/form-data">
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
                          data-content="عنوان شهر که اجباری است و در سایت نمایش داده می شود" data-original-title=""
                          title=""><i class="fa fa-question-circle"></i></span>
                    <input id="title" name="title" value="{{ old('title') ?: $city->title }}" tabindex="1"
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

                <div class="form-group">
                    <span class="field-force">*</span>
                    <label for="en_title">عنوان انگلیسی</label>
                    <span popover="" data-placement="top" data-trigger="hover"
                          data-content="عنوان انگلیسی شهر که اجباری است و در سایت نمایش داده می شود" data-original-title=""
                          title=""><i class="fa fa-question-circle"></i></span>
                    <input id="en_title" name="en_title" value="{{ old('en_title') ?: $city->en_title }}" tabindex="1"
                           placeholder="عنوان انگلیسی" type="text" class="form-control">
                    @if ($errors->has('en_title'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <strong>{{$errors->first('en_title')}}</strong>
                        </div>
                    @endif
                </div>
                <br/>




                @if(isset($countries) && $countries->count() > 0)
                    <div class="form-group">
                        <span class="field-force">*</span>
                        <label for="country_id">کشور</label>
                        <span popover="" data-placement="top" data-trigger="hover" data-content="کشور"
                              data-original-title="" title=""><i class="fa fa-question-circle"></i></span>
                        <select id="country_id" name="country_id" class="form-control" tabindex="2">
                            <option disabled selected>انتخاب</option>
                            @foreach($countries as $cat)
                                <option value="{{ $cat->id }}" {{ ($city->country->id  == $cat->id ? "selected": "") }}>{{ $cat->title }}</option>
                            @endforeach()
                        </select>

                    </div> <br/>
                @endif

                <br/>


                <div class="form-group">
                    <span class="field-force">*</span>
                    <label for="long">long</label>
                    <span popover="" data-placement="top" data-trigger="hover"
                          data-content="طول جغرافیایی" data-original-title=""
                          title=""><i class="fa fa-question-circle"></i></span>
                    <input id="long" name="long" value="{{ old('long') ?: $city->long }}" tabindex="1"
                           placeholder="long" type="text" class="form-control">
                    @if ($errors->has('long'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <strong>{{$errors->first('long')}}</strong>
                        </div>
                    @endif
                </div>
                <br/>
                <div class="form-group">
                    <span class="field-force">*</span>
                    <label for="lat">long</label>
                    <span popover="" data-placement="top" data-trigger="hover"
                          data-content="عرض جغرافیایی" data-original-title=""
                          title=""><i class="fa fa-question-circle"></i></span>
                    <input id="lat" name="lat" value="{{ old('lat') ?: $city->lat }}" tabindex="1"
                           placeholder="lat" type="text" class="form-control">
                    @if ($errors->has('lat'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <strong>{{$errors->first('lat')}}</strong>
                        </div>
                    @endif
                </div>
                <br/>


                <div class="form-group">
                    <span class="field-force"></span>
                    <label for="logo">تصویر شهر</label>
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
                formData.append("model", "cityLogo");
                formData.append("type", "logo");

            });

            myDropzone.on("success", function (file, response) {

                let c=$('#image_id_div').html();

                c=c+"<input type='hidden' name='logo_id' value="+response+">";

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
