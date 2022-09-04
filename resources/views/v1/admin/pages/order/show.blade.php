@extends('v1.admin.layout.default')
@section('content')
    <!-- title and breadcrumb -->
    <div class="clearfix">
        <div class="col-sm-6">
            <h2>جزئیات سفارش</h2>
        </div>
        <div class="col-sm-6 breadcrumb-col">
            <ol class="breadcrumb">
                <li><a href="{{route('admin.dashboard')}}">پیشخوان</a></li>
                <li><span><a href="{{route('admin.order.index')}}">مدیریت سفارش ها</a></span></li>
                <li><span>جزئیات سفارش</span></li>
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
                        <i class="fa fa-book sort-hand"></i>شماره {{$order->id}}
                        <div class="pan-btn expand"></div>
                    </div>
                    <div class="panel-body">
                        <div class="col-sm-12 user-profile-content">
                            <div class="tab-content">

                                <!-- profile -->
                                <div class="panel">
                                    <div class="tab-pane fade in active" id="prof">
                                        <h3 class="t#05a" style="padding-right: 20px; color: rgb(0, 85, 170);">شماره: <span>{{$order->id}}</span></h3>

                                        <div class="panel-body">
                                            <table class="table">
                                                <tbody>
                                                <tr class="first-table-row">
                                                    <td>شناسه سفارش:</td>
                                                    <td>{{ $order->id }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>شماره سفارش:</td>
                                                    <td>{{ $order->order_number }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>موبایل کاربر:</td>
                                                    <td>
                                                        @if(!empty($order->user))
                                                            <a style="padding-left: 20px;" href="{{ route('admin.user.show', $order->user->id) }}">{{ $order->user->mobile }}</a>
                                                        @else
                                                            کاربر حذف شده
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>کاربر:</td>
                                                    <td>
                                                        @if(!empty($order->user))
                                                            <a style="padding-left: 20px;" href="{{ route('admin.user.show', $order->user->id) }}">{{ $order->user->full_name }}</a>
                                                        @else
                                                            کاربر حذف شده
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>هدیه به کاربر دیگر؟</td>
                                                    <td>
                                                        @if(!empty($order->giftUser))
                                                            <a style="padding-left: 20px;" href="{{ route('admin.user.show', $order->giftUser->id) }}">{{ $order->giftUser->mobile }}</a>
                                                        @else
                                                            عدم هدیه
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>بسته موسیقی:</td>
                                                    <td>
                                                        @if(!empty($order->package))
                                                            <a style="padding-left: 20px;" href="{{ route('admin.package.show', $order->package->id) }}">{{ $order->package->title }}</a>
                                                        @else
                                                            بسته حذف شده
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>وضعیت سفارش:</td>
                                                    <td>{{$order->status->title}}</td>
                                                </tr>
                                                <tr>
                                                    <td>توضیحات:</td>
                                                    <td>{{$order->status->description}}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>قیمت کل:</td>
                                                    <td>{{ $order->total_price_toman }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>تخفیف:</td>
                                                    <td>{{ $order->discount_price_toman }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>قیمت قابل پرداخت</td>
                                                    <td>{{ $order->paid_price_toman }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>تاریخ ایجاد:</td>
                                                    <td>{{ $order->jalali_admin_created_at }}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td>آخرین بروزرسانی:</td>
                                                    <td>{{ $order->jalali_admin_updated_at }}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="panel-footer">
                                        <a href="{{route('admin.order.index')}}" class="btn btn-warning" title="بازگشت" tooltip><i class="fa fa-home"></i></a>
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
