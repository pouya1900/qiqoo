@extends('v1.admin.layout.default')
@section('content')
    <!-- title and breadcrumb -->
    <div class="clearfix">
        <div class="col-sm-6">
            <h2>گزارش ها</h2>
        </div>
        <div class="col-sm-6 breadcrumb-col">
            <ol class="breadcrumb">
                <li><a href="{{route('admin.dashboard')}}">پیشخوان</a></li>
                <li><span><a href="{{route('admin.report.index')}}">گزارش ها</a></span></li>
                <li><span>نمایش گزارش</span></li>
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
                        <i class="fa fa-book sort-hand"></i>{{$report->reportType->title}}
                        <div class="pan-btn expand"></div>
                    </div>
                    <div class="panel-body">
                        <div class="col-sm-12 user-profile-content">
                            <div class="tab-content">

                                <!-- profile -->
                                <div class="panel">
                                    <div class="tab-pane fade in active" id="prof">
                                        <h3 class="t#05a" style="color: rgb(0, 85, 170);">نام مخاطب: <span>{{ $report->user->username ?? $report->name }}</span></h3>

                                        <div class="panel-body">
                                            <table class="table">
                                                <tbody>
                                                <tr class="first-table-row">
                                                    <td> شماره تلفن:</td>
                                                    <td>{{$report->user->mobile ?? $report->mobile}}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td> ایمیل:</td>
                                                    <td>{{$report->user->email ?? $report->email}}</td>
                                                </tr>

                                                <tr>
                                                    <td>تاریخ گزارش:</td>
                                                    <td>{{$report->jalali_admin_created_at}}</td>
                                                </tr>
                                                <tr>
                                                    <td>تاریخ دیده شده:</td>
                                                    <td>{{$report->jalali_admin_seen_at}}</td>
                                                </tr>
                                                <tr>
                                                    <td>دیده شده برای اولین بار توسط ادمین:</td>
                                                    <td><a href="{{route('admin.user.show', $report->seenAdmin->id)}}"> {{ $report->seenAdmin->full_name ?? $report->seenAdmin->username }}</a></td>
                                                </tr>
                                                <tr>
                                                    <td>نوع گزارش:</td>
                                                    <td>{{$report->reportType->title}}</td>
                                                </tr>
                                                <tr>
                                                    <td>مربوط به:</td>
                                                    <td>{{$report->reportable_name}}</td>
                                                </tr>
                                                @if(isset($report->reportable->title))
                                                <tr>
                                                    <td>عنوان {{ $report->reportable_name }}:</td>
                                                    <td>
                                                        <a class="text-info" @if($report->reportable_type == 'App\Blog')
                                                                href="{{ route('blog.show', [$report->reportable_id, $report->reportable->url_title]) }}"
                                                            @else
                                                                href="{{ route('ads.show', [$report->reportable_id]) }}"
                                                            @endif
                                                        >
                                                            {{$report->reportable->title}}
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endif
                                                <tr>
                                                    <td>توضیح نوع  گزارش:</td>
                                                    <td>{{$report->reportType->description}}</td>
                                                </tr>
                                                <tr>
                                                    <td>درجه اهمیت گزارش:</td>
                                                    <td><b>{{$report->reportType->importance_level}}</b> از {{$report->reportType->max_importance_level}}</td>
                                                </tr>
                                                <tr>
                                                    <td>متن گزارش:</td>
                                                    <td>{{$report->text}}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="panel-footer">
                                        <a href="{{route('admin.report.index')}}" class="btn btn-warning"  title="بازگشت" tooltip><i class="fa fa-forward"></i></a>
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
