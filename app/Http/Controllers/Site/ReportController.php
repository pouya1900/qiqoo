<?php

namespace App\Http\Controllers\Site;

use App\Models\Ads;
use App\Events\SeenAdminEvent;
use Illuminate\Http\Request;
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

    public function store(Request $request,  $type, $id)
    {
        $this->validate($request, [
            'report_type_id' => 'required|integer|exists:report_types,id',
            'text' => 'required|min:3|max:500',
        ]);
        try{
            $values = [
                'user_id' => auth()->user()->id,
                'name' => auth()->user()->full_name,
                'mobile' => auth()->user()->mobile,
                'report_type_id' => $request->input('report_type_id'),
                'text' => $request->input('text'),
            ];

            if($type == 'ads'){
                $ads = $this->ads->fetch($id);
                $ads->reports()->create($values);
            }

            if($type == 'blog')
            {

            }

            $msg = 'با تشکر. گزارش شما با موفقیت ارسال شد!';
            return view('v1.index.pages.message', compact('msg'));
        }catch(\Exception $e) {
            $msg = 'متاسفانه ارسال گزارش ناموفق بود. لطفا مجددا تلاش کنید.';
            return view('v1.index.pages.message', compact('msg'));
        }
    }
}
