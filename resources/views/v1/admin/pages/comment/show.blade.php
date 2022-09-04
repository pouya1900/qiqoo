@extends('v1.admin.layout.default')
@section('content')
    <!-- title and breadcrumb -->
    <div class="clearfix">
        <div class="col-sm-6">
            <h2>دیدگاه ها</h2>
        </div>
        <div class="col-sm-6 breadcrumb-col">
            <ol class="breadcrumb">
                <li><a href="{{route('admin.dashboard')}}">پیشخوان</a></li>
                <li><span><a href="{{route('admin.comment.index')}}">دیدگاه ها</a></span></li>
                <li><span>نمایش دیدگاه</span></li>
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
                        <i class="fa fa-book sort-hand"></i>نمایش دیدگاه
                        <div class="pan-btn expand"></div>
                    </div>
                    <div class="panel-body">
                        <div class="col-sm-12 user-profile-content">
                            <div class="tab-content">

                                <!-- profile -->
                                <div class="panel">
                                    <div class="tab-pane fade in active" id="prof">
                                        <h3 class="t#05a" style="color: rgb(0, 85, 170);">نام مخاطب: <span>{{ isset($comment->user) ? $comment->user->full_name : $comment->name }}</span></h3>

                                        <div class="panel-body">
                                            <table class="table">
                                                <tbody>
                                                <tr class="first-table-row">
                                                    <td> شماره تلفن:</td>
                                                    <td>{{$comment->mobile ?: 'وارد نشده'}}</td>
                                                </tr>
                                                <tr class="first-table-row">
                                                    <td> ایمیل:</td>
                                                    <td>{{$comment->email ?: 'وارد نشده'}}</td>
                                                </tr>
                                                <tr>
                                                    <td>تاریخ پیام:</td>
                                                    <td>{{$comment->jalali_admin_created_at}}</td>
                                                </tr>
                                                <tr>
                                                    <td>تاریخ دیده شده:</td>
                                                    <td>{{$comment->jalali_admin_seen_at}}</td>
                                                </tr>
                                                <tr>
                                                    <td>دیده شده برای اولین بار توسط ادمین:</td>
                                                    <td><a href="{{route('admin.user.show', $comment->seenAdmin->id)}}"> @if ($comment->seenAdmin->first_name) {{$comment->seenAdmin->first_name}} {{ $comment->seenAdmin->last_name }} @else {{$comment->seenAdmin->username}} @endif</a></td>
                                                </tr>
                                                <tr>
                                                    <td>مربوط به:</td>
                                                    <td>{{$comment->commentable_name}}</td>
                                                </tr>
                                                @if(isset($comment->commentable->title))
                                                    <tr>
                                                        <td>عنوان {{ $comment->commentable_name }}:</td>
                                                        <td>
                                                            <a class="text-info" @if($comment->commentable_type == 'App\Blog')
                                                            href="{{ route('blog.show', [$comment->commentable_id, $comment->commentable->url_title]) }}"
                                                               @else
                                                               href="{{ route('ads.show', [$comment->commentable_id]) }}"
                                                                    @endif
                                                            >
                                                                {{$comment->commentable->title}}
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endif
                                                <tr>
                                                    <td>متن پیام:</td>
                                                    <td>{{$comment->text}}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="panel-footer">
                                        <a href="{{route('admin.comment.index')}}" class="btn btn-warning" d title="بازگشت" tooltip><i class="fa fa-forward"></i></a>
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
