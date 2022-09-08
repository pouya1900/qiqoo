@extends('v1.admin.layout.default')
@section('content')
    <!-- title and breadcrumb -->
    <div class="clearfix">
        <div class="col-sm-6">
            <h2>ویوها</h2>
        </div>
        <div class="col-sm-6 breadcrumb-col">
            <ol class="breadcrumb">
                <li><a href="{{route('admin.dashboard')}}">پیشخوان</a></li>
                <li><span><a href="{{route('admin.app-view.index')}}">مدیریت ویوها</a></span></li>
                <li><span>جزئیات ویو</span></li>
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
                        <i class="fa fa-book sort-hand"></i>{{$app_view->title}}
                        <div class="pan-btn expand"></div>
                    </div>
                    <div class="panel-body">
                        <div class="col-sm-12 user-profile-content">
                            <div class="tab-content">

                                <!-- profile -->
                                <div class="panel">
                                    <div class="tab-pane fade in active" id="prof">
                                        <h3 class="t#05a" style="padding-right: 20px;color: rgb(0, 85, 170);">عنوان:
                                            <span>{{$app_view->title}}</span></h3>

                                        <div class="panel-body">
                                            <table class="table">
                                                <tbody>
                                                <tr class="first-table-row">
                                                    <td>رنگ عنوان :</td>
                                                    <td>{{ $app_view->title_color_code }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>نوع ویو :</td>
                                                    <td>{{ $app_view->appViewType->type_title_fa }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>محتوا</td>
                                                    <td style="padding: 12px 0">
                                                        @if($app_view->appViewType->type_content=="ads")
                                                            @foreach($app_view->ads as $ads)
                                                                <span style="border: 1px solid #cfcfcf; padding: 3px;">{{$ads->title}}</span>
                                                            @endforeach
                                                            @else
                                                            @foreach($app_view->adsCategories as $adsCategories)
                                                                <span style="border: 1px solid #cfcfcf; padding: 3px;">{{$adsCategories->title}}</span>
                                                            @endforeach
                                                        @endif
                                                    </td>
                                                </tr>


                                                <tr class="first-table-row">
                                                    <td>وضعیت انتشار:</td>
                                                    <td>@if(!empty($app_view->published_at))<span
                                                                style="color: green">{{$app_view->jalaliAdminPublishedAt}}</span>@else
                                                            <span style="color: red">منتشر نشده</span>@endif</td>
                                                </tr>


                                                <tr class="first-table-row">
                                                    <td>توضیحات : </td>
                                                    <td>{{ $app_view->description }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>رنگ توضیحات : </td>
                                                    <td>{{ $app_view->description_color_code }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>رنگ پس زمینه : </td>
                                                    <td>{{ $app_view->background_color_code }}</td>
                                                </tr>

                                                <tr class="first-table-row">
                                                    <td>تصویر پس زمینه : </td>
                                                    <td><img src="{{ $app_view->backgroundImage['medium']}}" alt="{{ $app_view->title }}">
                                                    </td>                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>متن دکمه عملیات : </td>
                                                    <td>{{ $app_view->action }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>رنگ دکمه عملیات : </td>
                                                    <td>{{ $app_view->action_color_code }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>ترتیب</td>
                                                    <td>{{ $app_view->order }}</td>
                                                </tr>

                                                <tr class="first-table-row">
                                                    <td>نیاز به فضا دارد ؟</td>
                                                    <td>@if($app_view->need_space)<span
                                                                style="color: green">بله</span>@else<span
                                                                style="color: red">خیر</span>@endif</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>تاریخ ایجاد:</td>
                                                    <td>{{ $app_view->jalaliAdminCreatedAt }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>تاریخ ویرایش:</td>
                                                    <td>{{ $app_view->jalaliAdminUpdatedAt }}</td>
                                                </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="panel-footer">
                                        <a href="{{route('admin.app-view.index')}}" class="btn btn-warning" d title="بازگشت"
                                           tooltip><i class="fa fa-home"></i></a>
                                        <a href="{{route('admin.app-view.edit', $app_view->id)}}" class="btn btn-success"
                                           title="ویرایش" tooltip><i class="fa fa-pencil"></i></a>
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
