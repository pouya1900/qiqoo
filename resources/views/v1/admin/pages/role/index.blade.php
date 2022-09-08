@extends('v1.admin.layout.default')
@section('custom_style')
    @include('v1.admin.includes.datatable-styles')
@endsection
@section('content')
    <!-- title and breadcrumb -->
    <div class="clearfix">
        <div class="col-sm-6">
            <h2>مدیریت دسترسی ها</h2>
        </div>
        <div class="col-sm-6 breadcrumb-col">
            <ol class="breadcrumb">
                <li><a href="{{route('admin.dashboard')}}">پیشخوان</a></li>
                <li><a href="{{route('admin.role.index')}}">دسترسی ها</a></li>
                <li>{{$subSequence['title']}}</li>
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
                        <i class="fa fa-book sort-hand"></i>{{$subSequence['title']}}
                        <div class="pan-btn expand"></div>
                    </div>
                    <div class="panel-body">
                        <div class="well " style="transition: all 0.3s ease 0s;">
                            <a href="{{route('admin.role.create')}}" popover="" class="btn btn-warning well b#52D078" data-placement="top" data-trigger="hover" data-content="برای ایجاد رکورد جدید کلیک کنید" data-original-title="" title="">رکورد جدید</a>
                        </div>
                        @if(count($roles))
                            <div class="panel-body">
                                <h2>{{$subSequence['title']}}</h2><br/>
                                <table id="example" class="table table-condensed table-hover table-striped table-responsive data-table">
                                    <thead>
                                    <tr>
                                        <th data-column-id="id" >شناسه</th>
                                        <th data-column-id="title">عنوان</th>
                                        <th data-column-id="price">نام</th>
                                        <th data-column-id="created_at">زمان ایجاد</th>
                                        <th class="my_commands" data-column-id="commands" data-sortable="false" style="text-align: center">عملیات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($roles as $row)
                                        <tr class="clickable-row" data-url="{{route('admin.role.show', $row->id)}}">

                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $row->short_title }}</td>
                                            <td>{{ $row->name }}</td>
                                            <td>{{ $row->jalali_admin_created_at }}</td>
                                            <td>
                                                @include('v1.admin.includes.page-table-buttons', [
                                                'table_show_param'=>['route' => 'admin.role.show', 'params' => ['id' => $row->id, 'title' =>  $row->url_title]],
                                                'table_edit'=>['route' => 'admin.role.edit', 'id' => $row->id],
                                                ])
                                                @include('v1.admin.includes.page-table-buttons',  ['table_delete' => ['model_type' => 'role', 'id' => $row->id]])
                                            </td>
                                        </tr>
                                    @endforeach

                                    </tbody>

                                </table>
                                {!! $roles->render() !!}

                                @else
                                    <p>در حال حاضر اطلاعاتی وجود ندارد!</p>
                                @endif
                            </div>
                    </div>
                </div>
                <!-- /End Basic table -->
            </td>
        </tr>
    </table>
@endsection
@section('custom_script')
    @include('v1.admin.includes.datatable-scripts')
    <script type="text/javascript">
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
            @endif
            {{session()->forget('notifications')}}

        });
    </script>
@endsection