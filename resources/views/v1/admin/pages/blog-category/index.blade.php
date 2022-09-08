@extends('v1.admin.layout.default')
@section('custom_style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/css/treeview.css')}}"/>
    @include('v1.admin.includes.datatable-styles')
@endsection

@section('content')
    <!-- title and breadcrumb -->
    <div class="row clearfix">
        <div class="col-sm-6">
            <h2>مدیریت دسته بندی اخبار</h2>
        </div>
        <div class="col-sm-6 breadcrumb-col">
            <ol class="breadcrumb">
                <li><a href="{{route('admin.dashboard')}}">پیشخوان</a></li>
                <li><span>مدیریت محتوا</span></li>
                <li><a href="{{route('admin.blog-category.index')}}">مدیریت دسته بندی اخبار</a></li>
            </ol>
        </div>
    </div>
    <!-- /End title and breadcrumb -->

    <div class="panel" id="basic">
        <div class="panel-heading b#ffe7ff">
            <i class="fa fa-book sort-hand"></i>دسته بندی اخبار
            <div class="pan-btn expand"></div>
        </div>

        <div class="panel-body">
            <div class="well " style="transition: all 0.3s ease 0s; ">
                <a href="{{route('admin.blog-category.create')}}" popover="" class="btn btn-warning well b#52D078"
                   data-placement="top" data-trigger="hover" data-content="برای رکورد جدید کلیک کنید"
                   data-original-title="" title="">رکورد جدید</a>
            </div>

            @if(count($allCategories))
                <div class="panel-body">
                    <h2>نمایش جدولی</h2><br/>
                    <table id="example"
                           class="table table-condensed table-hover table-striped table-responsive data-table">
                        <thead>
                        <tr>
                            <th data-column-id="id">شناسه</th>
                            <th data-column-id="name">عنوان</th>
                            <th data-column-id="parent">والد</th>
                            <th data-column-id="color_code">کد تصویر</th>
                            <th data-column-id="order">ترتیب</th>
                            <th data-column-id="isFavorite">دسته بندی برگزیده</th>
                            <th data-column-id="admin">ایجاد کننده</th>
                            <th data-column-id="created_at">تاریخ ساخت</th>
                            <th class="my_commands" data-column-id="commands" data-sortable="false"
                                style="text-align: center">عملیات
                            </th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($allCategories as $category)
                            <tr class="clickable-row" data-url="{{route('admin.blog-category.edit', $category->id)}}">
                                <td>{{ $loop->index+1 }}</td>
                                <td>{{ $category->shortTitle }}</td>
                                <td>{{ $category->parent ? $category->parent->shortTitle : 'ریشه' }}</td>
                                <td>{{ $category->color_code }}</td>
                                <td>{{ $category->order }}</td>
                                <td>{{ $category->favoriteStatus  }}</td>
                                <td>{{  $category->admin ? ($category->admin->full_name ?: $category->admin->username) : "" }}</td>
                                <td>{{ $category->jalali_admin_created_at}}</td>
                                <td>
                                    @include('v1.admin.includes.page-table-buttons',[
                                        'table_delete' => ['model_type' => 'blogCategory', 'id' => $category->id],
                                        'table_edit'=>['route' => 'admin.blog-category.edit', 'id' => $category->id]
                                     ])
                                </td>
                            </tr>
                        @endforeach

                        </tbody>

                    </table>
                    {!! $allCategories->render() !!}
                </div>
            @else
                <p>در حال حاضر اطلاعاتی وجود ندارد!</p>
            @endif
        </div>
    </div>

@endsection

@section('custom_script')
    <script type="text/javascript" src="{{asset('assets/dashboard/js/treeview.js')}}"></script>
    @include('v1.admin.includes.datatable-scripts')
    <script type="text/javascript">
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
            @endif
            {{session()->forget('notifications')}}

        });
    </script>
@endsection
