<!-- Colorfull modals -->
@if(isset($register))
    <span class="btn b#78CD51" data-toggle="modal" data-target="#greenModal">ثبت</span>
    <div id="greenModal" class="modal fade">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header b#78CD51">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">ذخیره تغییرات</h4>
                </div>
                <div class="modal-body">تغییرات ذخیره شوند؟
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">بستن</button>
                    <button type="submit" class="btn b#78CD51">ذخیره ی تغییرات</button>
                </div>
            </div>
        </div>
    </div>
@endif

@if(isset($cancel))
    <span class="btn b#f1c500" data-toggle="modal" data-target="#yellowModal">انصراف</span>
    <div id="yellowModal" class="modal fade">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header b#f1c500">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">انصراف</h4>
                </div>
                <div class="modal-body">تغییرات حذف شوند؟
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">بستن</button>
                    <a @if(isset($cancel)) href="{{route($cancel['route'])}}" @endif class="btn b#f1c500">تایید</a>
                </div>
            </div>
        </div>
    </div>
@endif

{{--table buttons --}}
@if(isset($table_show))
    <a href="{{route($table_show['route'], $table_show['id'])}}" popover="" data-placement="top" data-trigger="hover"
       data-content="نمایش جزییات"><i class="fa fa-eye"></i></a>
@endif

@if(isset($table_edit))
    <a href="{{route($table_edit['route'], $table_edit['id'])}}" popover="" data-placement="top" data-trigger="hover"
       data-content="ویرایش این رکورد"><i class="fa fa-pencil"></i></a>
@endif

@if(isset($table_publish))
    <a href="#" onclick="publish( '{{$table_publish['model_type']}}', '{{$table_publish['id']}}')" popover=""
       data-placement="top" data-trigger="hover" data-content="منتشر کردن"><i class="fa fa-print"></i></a>
@endif
@if(isset($table_unpublish))
    <a href="#" onclick="publish('{{$table_unpublish['model_type']}}', '{{$table_unpublish['id']}}')" popover=""
       data-placement="top" data-trigger="hover" data-content="عدم انتشار"><i class="fa fa-ban "></i></a>
@endif

@if(isset($table_active))
    <a href="#" onclick="active( '{{$table_active['model_type']}}', '{{$table_active['id']}}')" popover="" popover=""
       data-placement="top" data-trigger="hover" data-content="تغییر وضعیت"><i class="fa fa-exchange"></i></a>
@endif

@if(isset($table_untrash))
    <a href="#" onclick="trash( '{{$table_untrash['model_type']}}', '{{$table_untrash['id']}}')" popover=""
       data-placement="top" data-trigger="hover" data-content="بازیابی این رکورد"><i class="fa fa-reply"></i></a>
@endif

@if(isset($table_trash))
    <a href="#" onclick="trash( '{{$table_trash['model_type']}}', '{{$table_trash['id']}}')" popover=""
       data-placement="top" data-trigger="hover" data-content="انتقال به سطل زباله"><i class="fa fa-trash"></i></a>
    {{--<span  class="btn b#ff6c60" data-toggle="modal" data-target="#table-deleteModal"><i class="fa fa-trash"></i></span>--}}
@endif

@if(isset($table_delete))
    <a href="#" onclick="delete_permanently('{{$table_delete['model_type']}}', '{{$table_delete['id']}}')" popover=""
       data-placement="top" data-trigger="hover" data-content="حذف دائمی"><i class="fa fa-close"></i></a>
@endif
