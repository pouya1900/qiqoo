<?php

namespace App\Http\Controllers\Administrator;

use App\Events\SeenAdminEvent;
use App\Http\Requests\Site\ReportRequest;
use App\Models\Ads;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdsController extends Controller
{
    protected $ads;

    public function __construct(Ads $ads)
    {
        $this->ads = $ads;
    }

    public function index()
    {
        $subSequence = ['id' => 0, 'title' => 'همه ی رکوردها'];
        $ads = $this->ads->orderByPagination();
        return view('v1.admin.pages.ads.index', compact('ads', 'subSequence'));
    }

    public function show($id)
    {

	    $ads = $this->ads->with(['category', 'user', 'city'])->findOrFail($id);

        if(empty($ads->seen_at)){
            event(new SeenAdminEvent($ads, auth()->user()->id));
        }

        return view('v1.admin.pages.ads.show', compact('ads'));
    }

    public function publish($id)
    {
        try {
            if(empty($ads = $this->ads->find($id))){
                throw new \Exception('داده مورد نظر شما یافت نشد!');
            }

            $ads->update([
                'published_at' => empty($ads->published_at) ? Carbon::now() : null,
                'published_admin_id' => empty($ads->published_at) ? auth()->user()->id : null
            ]);

            session()->flash('notifications', ['message' => 'رکورد مورد نظر  با موفقیت بروزرسانی شد', 'alert_type' => 'success']);
            $data = [
                'status' => 'success',
                'message' => 'رکورد مورد نظر با موفقیت بروزرسانی شد!',
                'data' => $ads,
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

    public function trash($id)
    {
        try {
            if(empty($ads = $this->ads->withTrashed()->findOrFail($id))){
                throw new \Exception('داده مورد نظر شما یافت نشد!');
            }

            if($ads->trashed()){
                $ads->restore();
                $message = 'رکورد مورد نظر با موفقیت بازیابی شد!';
            }else{
                $ads->delete();
                $message = 'رکورد مورد نظر با موفقیت به سطل زباله منتقل شد!';
            }
            session()->flash('notifications', ['message' => $message, 'alert_type' => 'success']);
            $data = [
                'status' => 'success',
                'message' => $message,
                'data' => [],
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

    public function trashed()
    {
        $subSequence = ['id' => 1, 'title' => 'سطل زباله'];
        $ads =  $this->ads->with(['category', 'user'])->onlyTrashed()->OrderByPagination();
        return view('v1.admin.pages.ads.index', compact('ads', 'subSequence'));
    }

    public function unPublished()
    {
        $subSequence = ['id' => 2, 'title' => 'رکوردهای منتشر نشده'];
        $ads = $this->ads->with(['category', 'user'])->unPublished()->OrderByPagination();
        return view('v1.admin.pages.ads.index', compact('ads', 'subSequence'));
    }
}
