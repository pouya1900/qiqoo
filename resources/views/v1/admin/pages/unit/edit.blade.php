@extends('v1.admin.layout.default')
@section('content')

<!-- title and breadcrumb -->
<div class="row clearfix">
    <div class="col-sm-6">
        <h2>ویرایش واحد</h2>
    </div>
    <div class="col-sm-6 breadcrumb-col">
        <ol class="breadcrumb">
            <li><a href="{{route('admin.dashboard')}}">پیشخوان</a></li>
            <li><span><a href="{{route('admin.unit.index')}}">مدیریت واحد ها</a></span></li>
            <li><span>ویرایش واحد</span></li>
        </ol>
    </div>
</div>
<!-- /End title and breadcrumb -->

<!-- Basic form -->
<div class="panel" id="basic">
    <div class="panel-heading b#c6f9ff">
        <i class="fa fa-pencil-square-o sort-hand"></i> {{ $unit->shortTitle }}
        <div class="pan-btn expand"></div>
    </div>
    <div class="panel-body">@include('v1.admin.includes.validation-error')
        <form action="{{route('admin.unit.update', $unit->id)}}" method="post" enctype="multipart/form-data">
            {{ method_field('put') }}
            {{ csrf_field() }}

            <div class="form-group">
                <span class="field-force">*</span>
                <label for="title">عنوان</label>
                <span data-toggle="popover" data-placement="top" data-trigger="hover" data-content="عنوان خبر که اجباری است و در سایت نمایش داده می شود" data-original-title="" title=""><i class="fa fa-question-circle"></i></span>
                <input id="title" name="title" value="{{ old('title') ?? $unit->title }}" tabindex="1" placeholder="عنوان" type="text" class="form-control">
                @if ($errors->has('title'))
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>{{$errors->first('title')}}</strong>
                    </div>
                @endif
            </div><br/>

            <div class="panel-body">
                @include('v1.admin.includes.page-table-buttons', ['register'=>TRUE, 'cancel' => ['route' => 'admin.unit.index']])
            </div>
        </form>
    </div>
</div>

<!-- /End Basic form -->
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
