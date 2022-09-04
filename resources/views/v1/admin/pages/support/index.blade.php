@extends('v1.admin.layout.default')
@section('custom_style')
    @include('v1.admin.includes.datatable-styles')
@endsection
@section('content')
    <!-- title and breadcrumb -->
    <div class="clearfix">
        <div class="col-sm-6">
            <h2>پشتیبانی</h2>
        </div>
        <div class="col-sm-6 breadcrumb-col">
            <ol class="breadcrumb">
                <li><a href="{{route('admin.dashboard')}}">پیشخوان</a></li>
                <li><a href="{{route('admin.support.index')}}">پشتیبانی</a></li>
                <li>{{$sub_sequence['title']}}</li>
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
                        <i class="fa fa-book sort-hand"></i>{{$sub_sequence['title']}}
                        <div class="pan-btn expand"></div>
                    </div>
                    <div class="panel-body">
                        @if(!empty($supports))
                            <div class="panel-body">
                                <h2>{{$sub_sequence['title']}}</h2><br/>
                                <table id="example" class="table table-condensed table-hover table-striped table-responsive data-table">
                                    <thead>
                                    <tr>
                                        <th data-column-id="id" >شناسه</th>
                                        <th data-column-id="parent">نام</th>
                                        <th data-column-id="admin">شماره تلفن</th>
                                        <th data-column-id="email">ایمیل</th>
                                        <th data-column-id="seen_at">زمان دیده شدن</th>
                                        {{--<th data-column-id="seenAdmin">دیده شده برای اولین بار توسط ادمین</th>--}}
                                        <th data-column-id="created_at">عنوان پیام</th>
                                        <th data-column-id="content">تاریخ</th>
                                        <th class="my_commands" data-column-id="commands" data-sortable="false" style="text-align: center">عملیات</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($supports as $row)
                                        <tr class="clickable-row" {{ empty($row->seen_at) ? "style=font-weight:bold;" : "" }}  data-url="{{route('admin.support.show', $row->id)}}">
                                            <td>{{ $loop->index+1 }}</td>
                                            <td>{{ Str::limit($row->name, $limit = 40, '[...]')}}</td>
                                            <td>{{ $row->mobile ?? 'وارد نشده' }} </td>
                                            <td>{{ $row->email ?? 'وارد نشده' }} </td>
                                            <td>{{ $row->jalali_admin_seen_at ?? 'دیده نشده' }} </td>
                                            <td>{{ Str::limit($row->text, $limit = 10, "[...]")}}</td>
                                            <td>{{ $row->jalali_admin_created_at }}</td>
                                            <td>
                                                @include('v1.admin.includes.page-table-buttons', [
                                                    'table_show'=>['route' => 'admin.support.show', 'id' => $row->id],
                                                    'table_delete' => ['model_type' => 'support', 'id' => $row->id],
                                                ])
                                            </td>
                                        </tr>
                                    @endforeach

                                    </tbody>

                                </table>
                                {!! $supports->render() !!}

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