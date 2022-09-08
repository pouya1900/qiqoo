<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Support\Facades\Auth;
use App\Events\SeenAdminEvent;
use Illuminate\Http\Request;
use App\Models\Support;

class SupportController extends Controller
{
    protected $support;
    public function __construct(Support $support)
    {
        $this->support = $support;
    }

    public function index()
    {
        $sub_sequence = ['id' => 0, 'title' => 'مدیریت پیام های پشتیبانی'];
        $supports = $this->support->orderByPagination();

        return view('v1.admin.pages.support.index', compact('supports', 'sub_sequence'));
    }

    public function show(Support $support)
    {
        if(empty($support->seen_at)){
            event(new SeenAdminEvent($support, auth()->user()->id));
        }
        return view('v1.admin.pages.support.show', compact('support'));
    }

    public function doDelete(Support $support)
    {
        try {
            $support->delete();

            session()->flash('notifications', ['message' => trans('messages.crud.deletedModelSuccess'), 'alert_type' => 'success']);

            $data = [
                'status' => 'success',
                'message' => trans('messages.crud.deletedModelSuccess'),
                'data' => $support,
                'code' => 200
            ];

            return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            $data = [
                'status' => 'fail',
                'message' => trans('messages.crud.deletedModelFail'),
                'data' => [],
                'code' => 400
            ];
            return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
        }
    }

    public function getContactUs()
    {
        return view('v1.site.pages.contact-us');
    }

    public function store(Request $request)
    {
        $rules = [
            'text' => 'required|string|max:2000|min:6',
            'title'  => 'required|string|min:3|max:250'
        ];
        if(!Auth::check()) {
            $rules = array_merge([
                'name' => 'required|string|max:100|min:3',
                'mobile' => 'required|digits:10',
                'email' => 'nullable|max:50|email',
            ], $rules);
        }
        $this->validate($request, $rules);

        if ($request->expectsJson()) {
            return response()->json(['valid' => true], 200);
        };
        try{
            $values = [];
            if(Auth::user())
            $values = [
                'name' => Auth::user()->full_name,
                'mobile' => Auth::user()->mobile ?: null,
                'email' => Auth::user()->profile->email ?: null,
                'title' => $request->input('title'),
                'text' => $request->input('text')
            ];
            if(!Auth::check()){
                $values = [
                    'name' => $request->input('name') ?: null,
                    'mobile' => $request->input('mobile') ?: null,
                    'email' => $request->input('email') ?: null,
                    'title' => $request->input('title'),
                    'text' => $request->input('text')
                ];
            }
            $this->support->create($values);
            $msg = 'با تشکر! پیام شما با موفقیت ارسال شد';
            return view('v1.site.pages.message', compact('msg'));
        }catch(\Exception $e) {
            $msg = 'متاسفانه ارسال پیام شما ناموفق بود. لطفا مجددا تلاش کنید';
            return view('v1.site.pages.message', compact('msg'));
        }
    }
}
