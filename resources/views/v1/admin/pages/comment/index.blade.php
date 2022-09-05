@extends('v1.admin.layout.default')
@section('custom_style')
    @include('v1.admin.includes.datatable-styles')
@endsection
@section('content')
    <!-- title and breadcrumb -->
    <div class="clearfix">
        <div class="col-sm-6">
            <h2>دیدگاه ها</h2>
        </div>
        <div class="col-sm-6 breadcrumb-col">
            <ol class="breadcrumb">
                <li><a href="{{route('admin.dashboard')}}">پیشخوان</a></li>
                <li><a href="{{route('admin.comment.index')}}">دیدگاه ها</a></li>
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
                        <i class="fa fa-book sort-hand"></i>مدیریت دیدگاه ها
                        <div class="pan-btn expand"></div>
                    </div>
                    <div class="panel-body">
                        @if(isset($comments) && count($comments))
                            <div class="panel-body">
                                <h2>{{$sub_sequence['title']}}</h2><br/>
                                <table id="example"
                                       class="table table-condensed table-hover table-striped table-responsive data-table">
                                    <thead>
                                    <tr>
                                        <th data-column-id="id" data-type="numeric">شناسه</th>
                                        <th data-column-id="parent">کاربر</th>
                                        <th data-column-id="name">تاریخ تایید</th>
                                        <th data-column-id="admin">تایید شده توسط</th>
                                        <th data-column-id="published_at">تاریخ دیده شدن</th>
                                        <th data-column-id="seen_at">دیده شدن توسط</th>
                                        <th data-column-id="created_at">تاریخ ارسال</th>
                                        <th data-column-id="commentable_name">مربوط به</th>
                                        <th class="my_commands" data-column-id="commands" data-sortable="false"
                                            style="text-align: center">عملیات
                                        </th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($comments as $row)
                                        <tr {{ empty($row->seen_at) ? "style=font-weight:bold;" : "" }} class="clickable-row"
                                            data-url="{{route('admin.comment.show', $row->id)}}">
                                            <td>{{ $loop->index+1 }}</td>
                                            <td>{{ !empty($row->user) ? $row->user->fullNmae : $row->name}}</td> {{-- todo check this user info --}}
                                            <td>{{ $row->published_at ? $row->jalali_admin_published_at : 'تایید نشده'}}</td>
                                            <td>{{ $row->published_at ? $row->publishedAdmin->full_name : 'تایید نشده' }}</td>
                                            <td>{{ $row->seen_at ? $row->jalali_admin_seen_at : 'دیده نشده' }}</td>
                                            <td>{{ $row->seen_at ? $row->seenAdmin->full_name : 'دیده نشده'  }}</td>
                                            <td>{{ $row->jalali_admin_created_at }}</td>
                                            <td>{{ $row->commentable_name }}</td>
                                            <td>
                                                @include('v1.admin.includes.page-table-buttons', [
                                                    'table_show'=>['route' => 'admin.comment.show', 'id' => $row->id],
                                                    'table_publish'=>['model_type' => 'comment', 'id' => $row->id],
                                                    '$table_unpublish'=>['model_type' => 'comment', 'id' => $row->id],
                                                    'table_delete' => ['model_type' => 'comment', 'id' => $row->id],
                                                ])
                                            </td>
                                        </tr>
                                    @endforeach

                                    </tbody>

                                </table>
                                {!! $comments->render() !!}

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
