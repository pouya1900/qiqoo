@extends('v1.admin.layout.default')
@section('content')
    <!-- title and breadcrumb -->
    <div class="clearfix">
        <div class="col-sm-6">
            <h2>آگهی</h2>
        </div>
        <div class="col-sm-6 breadcrumb-col">
            <ol class="breadcrumb">
                <li><a href="{{route('admin.dashboard')}}">پیشخوان</a></li>
                <li><span><a href="{{route('admin.ads.index')}}">مدیریت آگهی</a></span></li>
                <li><span>جزئیات آگهی</span></li>
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
                        <i class="fa fa-book sort-hand"></i>{{$ads->title}}
                        <div class="pan-btn expand"></div>
                    </div>
                    <div class="panel-body">
                        <div class="col-sm-12 user-profile-content">
                            <div class="tab-content">

                                <!-- profile -->
                                <div class="panel">
                                    <div class="tab-pane fade in active" id="prof">
                                        <h3 class="t#05a" style="padding-right: 20px;color: rgb(0, 85, 170);">عنوان: <span>{{$ads->title}}</span></h3>

                                        <div class="panel-body">
                                            <table class="table">
                                                <tbody>
                                                <tr class="first-table-row">
                                                    <td>عنوان لاتین:</td>
                                                    <td>{{ $ads->en_title }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>متا عنوان:</td>
                                                    <td>{{ $ads->meta_title }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>متا کلمات کلیدی:</td>
                                                    <td>{{ $ads->meta_keywords }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>متا توضیحات:</td>
                                                    <td>{{ $ads->meta_description }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>متا نویسنده:</td>
                                                    <td>{{ $ads->meth_author }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>دسته بندی:</td>
                                                    <td>{{ empty($ads->category) ? '' : $ads->category->title }}</td>
                                                </tr>

                                                <tr class="first-table-row">
                                                    <td>تعداد بازدیدها:</td>
                                                    <td>{{ $ads->view }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>تعداد لایک ها:</td>
                                                    <td>{{ $ads->like }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>تعداد دیدگاه ها:</td>
                                                    <td>{{ $ads->comment }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>میانگین امتیاز:</td>
                                                    <td>{{ $ads->score }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>وضعیت انتشار:</td>
                                                    <td>@if(!empty($ads->published_at))<span style="color: green">{{$ads->jalaliAdminPublishedAt}}</span>@else<span style="color: red">منتشر نشده</span>@endif</td>
                                                </tr>

                                                <tr class="first-table-row">
                                                    <td>منتشر شده توسط:</td>
                                                    <td>@if(!empty($ads->published_at))<span style="color: green">{{$ads->publishedAdmin["fullName"]}}</span>@else<span style="color: red">منتشر نشده</span>@endif</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>دیده شده برای اولین بار توسط:</td>
                                                    <td>@if(!empty($ads->seen_at))<span style="color: green">{{$ads->seenAdmin->fullName}}</span>@else<span style="color: red">دیده نشده</span>@endif</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>توسط:</td>
                                                    <td>{{ empty($ads->user) ? '' : $ads->user->fullName }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>تصویر:</td>
                                                    <td><img src="{{ $ads->logo['small']}}" alt="{{ $ads->title }}"></td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>تاریخ ایجاد:</td>
                                                    <td>{{ $ads->jalaliAdminCreatedAt }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>تاریخ ویرایش:</td>
                                                    <td>{{ $ads->jalaliAdminUpdatedAt }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>توضیح کوتاه:</td>
                                                    <td>{{ $ads->short_description }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>توضیح کوتاه لاتین:</td>
                                                    <td>{{ $ads->en_short_description }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>ویدئو:</td>
                                                    <td>
                                                        <video width="320" height="240" controls>
                                                            <source src="{{$ads->video_link}}" type="">
                                                        </video>
                                                    </td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>تلفن:</td>
                                                    <td>{{ $ads->phone }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>موبایل:</td>
                                                    <td>{{ $ads->mobile }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>فیسبوک:</td>
                                                    <td>{{ $ads->facebook }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>اینستاگرام:</td>
                                                    <td>{{ $ads->instagram }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>توییتر:</td>
                                                    <td>{{ $ads->twitter }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>یوتیوب:</td>
                                                    <td>{{ $ads->youtube }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>ایمیل:</td>
                                                    <td>{{ $ads->email }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>آدرس:</td>
                                                    <td>{{ $ads->address }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>آدرس لاتین</td>
                                                    <td>{{ $ads->en_address }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>lat:</td>
                                                    <td>{{ $ads->lat }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>long:</td>
                                                    <td>{{ $ads->long }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>شهر:</td>
                                                    <td>{{ empty($ads->city) ? '' : $ads->city->title }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>کد پستی:</td>
                                                    <td>{{ $ads->postal_code }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>کد:</td>
                                                    <td>{{ $ads->code }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>تاریخ شروع آگهی:</td>
                                                    <td>{{ $ads->start_date }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>تاریخ پایان آگهی:</td>
                                                    <td>{{ $ads->end_date }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>تمدید شده است؟:</td>
                                                    <td>{{ empty($ads->is_extended) ? 'خیر' : 'بله' }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>تعداد دفعات تمدید؟:</td>
                                                    <td>{{ $ads->extended_time }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>فوری است؟:</td>
                                                    <td>{{ empty($ads->is_immediate) ? 'خیر' : 'بله' }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>ویژه است؟:</td>
                                                    <td>{{ empty($ads->is_vip) ? 'خیر' : 'بله' }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>رایگان است؟:</td>
                                                    <td>{{ empty($ads->is_free) ? 'خیر' : 'بله' }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>پرداخت شده است؟:</td>
                                                    <td>{{ empty($ads->is_pay) ? 'خیر' : 'بله' }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>بالای آگهی ها قرار گیرد؟:</td>
                                                    <td>{{ empty($ads->is_top) ? 'خیر' : 'بله' }}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="panel-footer">
                                        <a href="{{route('admin.ads.index')}}" class="btn btn-warning" d title="بازگشت" tooltip><i class="fa fa-home"></i></a>
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
