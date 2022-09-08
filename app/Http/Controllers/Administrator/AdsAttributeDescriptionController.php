<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Requests\Administrator\AdsAttributeDescriptionRequest;
use App\Models\AdsAttributeDescription;
use App\Models\AdsAttributeDescriptionValueType;
use App\Models\AdsCategory;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;

class AdsAttributeDescriptionController extends Controller
{
    private $adsAttributeDescription;
    private $adsAttributeDescriptionValueType;
    private $category;
    private $unit;

    public function __construct(AdsAttributeDescription $adsAttributeDescription, AdsCategory $category, AdsAttributeDescriptionValueType $adsAttributeDescriptionValueType, Unit $unit)
    {
        $this->adsAttributeDescription = $adsAttributeDescription;
        $this->adsAttributeDescriptionValueType = $adsAttributeDescriptionValueType;
        $this->category = $category;
        $this->unit = $unit;
    }

    public function index()
    {
        $subSequence = ['id' => 0, 'title' => 'همه ی رکوردها'];
        $adsAttributeDescription = $this->adsAttributeDescription->with(['categories', 'adsAttributeDescriptionValueType', 'admin'])->orderByPagination();
        return view('v1.admin.pages.ads-attribute-description.index', compact('adsAttributeDescription', 'subSequence'));
    }

    public function create()
    {
        $categories = $this->category->all();
        $units = $this->unit->all();
        $adsAttributeDescriptionValueTypes = $this->adsAttributeDescriptionValueType->all();

        if (empty($categories->count())) {
            session()->flash('notifications', ['message' => 'دسته بندی ها خالی است. لطفا ابتدا دسته بندی ها را بسازید.', 'alert_type' => 'error']);
            return redirect()->route('admin.category.index');
        }

        return view('v1.admin.pages.ads-attribute-description.create', compact('categories', 'adsAttributeDescriptionValueTypes', 'units'));
    }

    public function store(AdsAttributeDescriptionRequest $request)
    {
        try {
            $this->validate($request, [
                'title' => 'unique:ads_attribute_descriptions,title'
            ]);

            DB::beginTransaction();
            $request->merge([
                'admin_id' => auth()->user()->id,
                'field_name' => 'att_' . $request->field_name
            ]);

            $adsAttributeDescription = $this->adsAttributeDescription->create($request->all());
            $adsAttributeDescription->categories()->sync($request->category_id);

            DB::commit();

            session()->flash('notifications', ['message' => 'رکورد جدید با موفقیت اضافه شد', 'alert_type' => 'success']);
            return redirect()->route('admin.ads-attribute.index');
        } catch (\Exception $e) {
            DB::rollBack();

            session()->flash('notifications', ['message' => $e->getMessage() . 'خطا در انجام عملیات', 'alert_type' => 'error']);
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $adsAttributeDescription = $this->adsAttributeDescription->findOrFail($id);
        $categories = $this->category->all();
        $units = $this->unit->all();
        $adsAttributeDescriptionValueTypes = $this->adsAttributeDescriptionValueType->all();
        return view('v1.admin.pages.ads-attribute-description.edit', compact('adsAttributeDescription', 'categories', 'adsAttributeDescriptionValueTypes', 'units'));
    }

    public function update(AdsAttributeDescriptionRequest $request, $id)
    {
        try {
            $adsAttributeDescription = $this->adsAttributeDescription->findOrFail($id);
            $this->validate($request, [
                'title' => 'unique:ads_attribute_descriptions,title,' . $adsAttributeDescription->id
            ]);

            DB::beginTransaction();
            $request->merge([
                'admin_id' => auth()->user()->id,
                'field_name' => strpos($request->field_name, 'att_') !== false ? $request->field_name : 'att_' . $request->field_name
            ]);

            $adsAttributeDescription->update($request->all());
            $adsAttributeDescription->categories()->sync($request->category_id);

            DB::commit();

            session()->flash('notifications', ['message' => 'رکورد مورد نظر با موفقیت ویرایش شد', 'alert_type' => 'success']);
            return redirect()->route('admin.ads-attribute.index');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('notifications', ['message' => $e->getMessage() . 'خطا در انجام عملیات', 'alert_type' => 'error']);
            return redirect()->back();
        }
    }

    public function doDelete(AdsAttributeDescription $adsAttributeDescription)
    {
        try {
            if (!empty($adsAttributeDescription->categories->count())) {
                $adsAttributeDescription->categories()->detach();
            }

            $adsAttributeDescription->delete();
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
