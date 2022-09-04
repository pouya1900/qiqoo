@extends('v1.admin.layout.default')
@section('content')
    <!-- title and breadcrumb -->
    <div class="clearfix">
        <div class="col-sm-6">
            <h2>اخبار</h2>
        </div>
        <div class="col-sm-6 breadcrumb-col">
            <ol class="breadcrumb">
                <li><a href="{{route('admin.dashboard')}}">پیشخوان</a></li>
                <li><span><a href="{{route('admin.blog.index')}}">مدیریت اخبار</a></span></li>
                <li><span>جزئیات اخبار</span></li>
            </ol>
        </div>

    </div>
    <!-- /End title and breadcrumb -->

    <table id="content_table">
        <tr class="row1">
            <td id="column0" class="connectcolumn" colspan="2">

                <!-- Basic table -->
                <div class="panel" id="basic">
                    <div class="panel-heading b#ffe7ff">
                        <i class="fa fa-book sort-hand"></i>{{$blog->shortTitle}}
                        <div class="pan-btn expand"></div>
                    </div>
                    <div class="panel-body">
                        <div class="col-sm-12 user-profile-content">
                            <div class="tab-content">

                                <!-- profile -->
                                <div class="panel">
                                    <div class="tab-pane fade in active" id="prof">
                                        <h3 class="t#05a" style="padding-right: 20px;color: rgb(0, 85, 170);">عنوان: <span>{{$blog->title}}</span></h3>

                                        <div class="panel-body">
                                            <table class="table">
                                                <tbody>
                                                <tr class="first-table-row">
                                                    <td>عنوان لاتین:</td>
                                                    <td>{{ $blog->en_title }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>متا عنوان:</td>
                                                    <td>{{ $blog->meta_title }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>متا کلمات کلیدی:</td>
                                                    <td>{{ $blog->meta_keywords }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>متا توضیحات:</td>
                                                    <td>{{ $blog->meta_description }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>متا نویسنده:</td>
                                                    <td>{{ $blog->meth_author }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>دسته بندی:</td>
                                                    <td>{{ $blog->category->title }}</td>
                                                </tr>

                                                <tr class="first-table-row">
                                                    <td>تعداد بازدیدها:</td>
                                                    <td>{{ $blog->view }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>تعداد لایک ها:</td>
                                                    <td>{{ $blog->like }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>تعداد دیدگاه ها:</td>
                                                    <td>{{ $blog->comment }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>میانگین امتیاز:</td>
                                                    <td>{{ $blog->score }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>وضعیت انتشار:</td>
                                                    <td>@if(!empty($blog->published_at))<span style="color: green">{{$blog->jalaliAdminPublishedAt}}</span>@else<span style="color: red">منتشر نشده</span>@endif</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>نمایش در اسلایدر</td>
                                                    <td>@if($blog->slider_blog)<span style="color: green">بله</span>@else<span style="color: red">خیر</span>@endif</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>توسط:</td>
                                                    <td>{{ $blog->admin->full_name }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>لوگو:</td>
                                                    <td><img src="{{ $blog->logo['medium']}}" alt="{{ $blog->title }}"></td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>تصاویر:</td>
                                                    <td>
                                                        @foreach( $blog->contentImages as $image)
                                                        <img src="{{$image['small']}}" alt="{{ $blog->title }}">
                                                            @endforeach
                                                    </td>

                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>تاریخ ایجاد:</td>
                                                    <td>{{ $blog->jalaliAdminCreatedAt }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>تاریخ ویرایش:</td>
                                                    <td>{{ $blog->jalaliAdminUpdatedAt }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>توضیح کوتاه:</td>
                                                    <td>{{ $blog->short_description }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>توضیح کوتاه لاتین:</td>
                                                    <td>{{ $blog->en_short_description }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>محتوا:</td>
                                                    <td>{{ $blog->text }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>محتوای لاتین:</td>
                                                    <td>{{ $blog->en_text }}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="panel-footer">
                                        <a href="{{route('admin.blog.index')}}" class="btn btn-warning" d title="بازگشت" tooltip><i class="fa fa-home"></i></a>
                                        <a href="{{route('admin.blog.edit', $blog->id)}}" class="btn btn-success" title="ویرایش" tooltip><i class="fa fa-pencil"></i></a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </table>
@endsection

@section('custom_script')
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
