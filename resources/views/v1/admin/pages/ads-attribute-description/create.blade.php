@extends('v1.admin.layout.default')
@section('custom_style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/css/bootstrap-multiselect.css') }}">
@endsection
@section('content')
    <!-- title and breadcrumb -->
    <div class="row clearfix">
        <div class="col-sm-6">
            <h2>ویژگی جدید</h2>
        </div>
        <div class="col-sm-6 breadcrumb-col">
            <ol class="breadcrumb">
                <li><a href="{{route('admin.dashboard')}}">پیشخوان</a></li>
                <li><span><a href="{{route('admin.ads-attribute.index')}}">مدیریت ویژگی ها</a></span></li>
                <li><span>ویژگی جدید</span></li>
            </ol>
        </div>
    </div>
    <!-- /End title and breadcrumb -->

    <!-- Basic form -->
    <div class="panel" id="basic">
        <div class="panel-heading b#c6f9ff">
            <i class="fa fa-plus-square-o  sort-hand"></i>ویژگی جدید
            <div class="pan-btn expand"></div>
        </div>
        <div class="panel-body">
            @include('v1.admin.includes.validation-error')
            <form action="{{route('admin.ads-attribute.store')}}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="form-group">
                    <span class="field-force">*</span>
                    <label for="title">عنوان</label>
                    <span data-toggle="popover" data-placement="top" data-trigger="hover"
                          data-content="عنوان خبر که اجباری است و در سایت نمایش داده می شود" data-original-title=""
                          title=""><i class="fa fa-question-circle"></i></span>
                    <input id="title" name="title" value="{{ old('title') }}" placeholder="عنوان"
                           type="text" class="form-control">
                    @if ($errors->has('title'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                            <strong>{{$errors->first('title')}}</strong>
                        </div>
                    @endif
                </div>
                <br/>

                <div class="form-group">
                    <span class="field-force">*</span>
                    <label for="field_name">نام فیلد</label>
                    <span data-toggle="popover" data-placement="top" data-trigger="hover"
                          data-content="نام فیلد که در زمان افزودن آگهی باید مقدارش وارد شود" data-original-field_name=""
                          title=""><i class="fa fa-question-circle"></i></span>
                    <input id="field_name" name="field_name" value="{{ old('field_name') }}" placeholder="نام فیلد"
                           type="text" class="form-control">
                    @if ($errors->has('field_name'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                            <strong>{{$errors->first('field_name')}}</strong>
                        </div>
                    @endif
                </div>
                <br/>

                <div class="form-group">
                    <span class="field-force">*</span>
                    <label for="single-select-att-value">نوع مقدار ویژگی</label>
                    <span data-toggle="popover" data-placement="top" data-trigger="hover"
                          data-content="این فیلد مشخص میکند که کاربران در زمان ثبت آگهی و انتخاب این ویژگی، داده ورودی را چگونه وارد کنند و از چه نوعی باید باشد"><i
                            class="fa fa-question-circle"></i></span>
                    <br>
                    <select class="form-control" id="single-select-att-value"
                            name="ads_attribute_description_value_type_id">
                        @foreach($adsAttributeDescriptionValueTypes as $row)
                            <option
                                value="{{ $row->id }}" {{ (old('ads_attribute_description_value_type_id')  == $row->id ? "selected": "") }}>{{ $row->title }}</option>
                        @endforeach()
                    </select>

                    @if ($errors->has('ads_attribute_description_value_type_id'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                            <strong>{{$errors->first('ads_attribute_description_value_type_id')}}</strong>
                        </div>
                    @endif
                </div>
                <br/>

                <div class="form-group">
                    <label for="single-select-att-value2">واحد</label>
                    <br>
                    <select class="form-control " id="single-select-att-value2" name="unit_id" >
                        @foreach($units as $unit)
                            <option
                                value="{{ $unit->id }}" {{ (collect(old('unit_id'))->contains($unit->id)  ? "selected": "") }}>{{ $unit->shortTitle }}</option>
                        @endforeach()
                    </select>

                    @if ($errors->has('unit_id'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                            <strong>{{$errors->first('unit_id')}}</strong>
                        </div>
                    @endif
                </div>
                <br/>

                <div class="form-group">
                    <span class="field-force">*</span>
                    <label for="multiple-select-category">دسته بندی</label>
                    <br>
                    <select class="form-control " id="multiple-select-category" multiple="multiple" name="category_id[]">
                        @foreach($categories as $cat)
                            <option
                                value="{{ $cat->id }}" {{ (collect(old('category_id'))->contains($cat->id)  ? "selected": "") }}>{{ $cat->shortTitle }}</option>
                        @endforeach()
                    </select>

                    @if ($errors->has('category_id'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                            <strong>{{$errors->first('category_id')}}</strong>
                        </div>
                    @endif
                </div>
                <br/>

                <div class="panel-body">
                    @include('v1.admin.includes.page-table-buttons', ['register'=>TRUE, 'cancel' => ['route' => 'admin.ads-attribute.index']])
                </div>
            </form>
        </div>
    </div>

    <!-- /End Basic form -->
@endsection
@section('custom_script')
    <script type="text/javascript" src="{{ asset('assets/dashboard/js/bootstrap-multiselect.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#multiple-select-category').multiselect({
                enableCaseInsensitiveFiltering: true,
                includeSelectAllOption: true
            });

            $('#single-select-att-value').multiselect({
                enableCaseInsensitiveFiltering: true
            });

            $('#single-select-att-value2').multiselect({
                enableCaseInsensitiveFiltering: true
            });
        });


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
            {{session()->forget('notifications')}}
            @endif
        });
    </script>
@endsection
