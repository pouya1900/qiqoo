<?php

namespace App\Http\Controllers\Site;

use App\Http\Requests\Site\SupportRequest;
use App\Models\Support;

/**
 * Class SupportController
 * @package App\Http\Controllers\Site
 */
class SupportController extends Controller
{
    /**
     * @var Support
     */
    private $support;

    /**
     * SupportController constructor.
     * @param Support $support
     */
    public function __construct(Support $support)
    {
        $this->support = $support;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getContactUs()
    {
        return view('v1.site.pages.other.contact-us');
    }

    /**
     * @param SupportRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addSupportMessage(SupportRequest $request)
    {
        try{
            if(auth()->check()) {
                $request->merge([
                    'name' => auth()->user()->full_name,
                    'mobile' => auth()->user()->mobile,
                    'email' => auth()->user()->email,
                ]);
            }

            $this->support->create($request->all());

            $msg = trans('messages.support.success');
            return view('v1.site.pages.other.message', compact('msg'));
        }catch(\Exception $e) {
            $msg = trans('messages.support.failed');
            return view('v1.site.pages.other.message', compact('msg'));
        }
    }
}
