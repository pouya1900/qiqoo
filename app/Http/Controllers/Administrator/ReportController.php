<?php

namespace App\Http\Controllers\Administrator;

use App\Models\Ads;
use App\Events\SeenAdminEvent;
use App\Models\Report;
class ReportController extends Controller
{
    protected $report;
    protected $ads;
    public function __construct(Report $report, Ads $ads)
    {
        $this->report = $report;
        $this->ads = $ads;
    }

    public function index()
    {
        $sub_sequence = ['id' => 0, 'title' => 'مدیریت گزارش ها'];
        $reports = $this->report->OrderByPagination();
        return view('v1.admin.pages.report.index', compact('reports', 'sub_sequence'));
    }

    public function show(Report $report)
    {
        if(empty($report->seen_at)){
            event(new SeenAdminEvent($report, auth()->user()->id));
        }
        return view('v1.admin.pages.report.show', compact('report'));
    }

    public function doDelete(Report $report)
    {
        try {
            $report->delete();
            session()->flash('notifications', ['message' => 'عملیات با موفقیت انجام شد', 'alert_type' => 'success']);
            $data = [
                'status' => 'success',
                'message' => 'عملیات با موفقیت انجام شد',
                'data' => ['alireza'],
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
