@extends('v1.admin.layout.default')
@section('content')
    <!-- title and breadcrumb -->
    <div class="clearfix">
        <div class="col-sm-6">
            <h2>پشتیبانی</h2>
        </div>
        <div class="col-sm-6 breadcrumb-col">
            <ol class="breadcrumb">
                <li><a href="{{route('admin.dashboard')}}">پیشخوان</a></li>
                <li><span><a href="{{route('admin.support.index')}}">پشتیبانی</a></span></li>
                <li><span>نمایش پیام</span></li>
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
                        <i class="fa fa-book sort-hand"></i>{{$support->title}}
                        <div class="pan-btn expand"></div>
                    </div>
                    <div class="panel-body">
                        <div class="col-sm-12 user-profile-content">
                            <div class="tab-content">

                                <!-- profile -->
                                <div class="panel">
                                    <div class="tab-pane fade in active" id="prof">
                                        <h3 class="t#05a" style="color: rgb(0, 85, 170);">نام مخاطب: <span>{{$support->name}}</span></h3>

                                        <div class="panel-body">
                                            <table class="table">
                                                <tbody>
                                                <tr class="first-table-row">
                                                    <td> شماره تلفن:</td>
                                                    <td>{{$support->mobile}}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td> ایمیل:</td>
                                                    <td>{{$support->email}}</td>
                                                </tr>
                                                <tr>
                                                    <td>تاریخ پیام:</td>
                                                    <td>{{$support->jalali_admin_created_at}}</td>
                                                </tr>
                                                <tr>
                                                    <td>تاریخ دیده شده:</td>
                                                    <td>{{$support->jalali_admin_seen_at}}</td>
                                                </tr>
                                                @if(!empty($support->seen_at))
                                                <tr>
                                                    <td>دیده شده برای اولین بار توسط ادمین:</td>
                                                    <td><a href="{{route('admin.user.show', $support->seen_admin_id)}}">{{ $support->seenAdmin->full_name }}</a></td>
                                                </tr>
                                                @endif
                                                <tr>
                                                    <td>عنوان پیام:</td>
                                                    <td>{{$support->title}}</td>
                                                </tr>
                                                <tr>
                                                    <td>متن پیام:</td>
                                                    <td>{{$support->text}}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="panel-footer">
                                        <a href="{{route('admin.support.index')}}" class="btn btn-warning" d title="بازگشت" tooltip><i class="fa fa-forward"></i></a>
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
