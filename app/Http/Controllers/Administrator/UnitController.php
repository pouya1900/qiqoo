<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Requests\Administrator\UnitRequest;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;

class UnitController extends Controller
{
    private $unit;

    public function __construct(Unit $unit)
    {
        $this->unit = $unit;
    }

    public function index()
    {
        $subSequence = ['id' => 0, 'title' => 'همه ی رکوردها'];
        $units = $this->unit->with('admin')->orderByPagination();
        return view('v1.admin.pages.unit.index', compact('units', 'subSequence'));
    }

    public function create()
    {
        return view('v1.admin.pages.unit.create');
    }

    public function store(UnitRequest $request)
    {
        try {
            $this->validate($request, [
                'title' => 'unique:units,title'
            ]);

            DB::beginTransaction();
            $request->merge([
                'admin_id' => auth()->user()->id
            ]);

            $unit = $this->unit->create($request->all());

            DB::commit();

            session()->flash('notifications', ['message' => 'رکورد جدید با موفقیت اضافه شد', 'alert_type' => 'success']);
            return redirect()->route('admin.unit.index');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('notifications', ['message' => $e->getMessage() . 'خطا در انجام عملیات', 'alert_type' => 'error']);
            return redirect()->back();
        }
    }

    public function edit(Unit $unit)
    {
        return view('v1.admin.pages.unit.edit', compact('unit'));
    }

    public function update(UnitRequest $request, Unit $unit)
    {
        try {
            $this->validate($request, [
                'title' => 'unique:units,title,' . $unit->id
            ]);

            DB::beginTransaction();
            $request->merge([
                'admin_id' => auth()->user()->id
            ]);

            $unit->update($request->all());

            DB::commit();

            session()->flash('notifications', ['message' => 'رکورد مورد نظر با موفقیت ویرایش شد', 'alert_type' => 'success']);
            return redirect()->route('admin.unit.index');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('notifications', ['message' => $e->getMessage() . 'خطا در انجام عملیات', 'alert_type' => 'error']);
            return redirect()->back();
        }
    }

    public function doDelete(Unit $unit)
    {
        try {
            if (!empty($unit->adsAttributeDescriptions()->count())) {
                $message = 'ویژگی هایی با این واحد موجود است. لطفا ابتدا واحد ویژگی موردنظر را تغییر دهید';
                session()->flash('notifications', ['message' => $message, 'alert_type' => 'warning']);
                $data = [
                    'status' => 'fault',
                    'message' => 'خطا: ' . $message,
                    'data' => [],
                    'code' => 400
                ];
                return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
            }

            $unit->delete();
            session()->flash('notifications', ['message' => 'عملیات با موفقیت انجام شد', 'alert_type' => 'success']);
            $data = [
                'status' => 'success',
                'message' => 'عملیات با موفقیت انجام شد',
                'data' => ['well-done'],
                'code' => 200
            ];
            return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            session()->flash('notifications', ['message' => 'خطا در انجام عملیات: لطفا با مدیر سایت تماس بگیرید.', 'alert_type' => 'warning']);
            $data = [
                'status' => 'fault',
                'message' => 'خطا: ' . $e->getMessage(),
                'data' => [],
                'code' => 400
            ];
            return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
