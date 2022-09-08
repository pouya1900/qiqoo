<?php

namespace App\Http\Controllers\Api\Support;

use App\Http\Requests\Api\SupportRequest;
use App\Models\Support;
use App\Http\Controllers\Api\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class SupportController
 * @package App\Http\Controllers\Api\Support
 */
class SupportController extends Controller
{
    /**
     * @var Support
     */
    private $support;
    /**
     * @var Request
     */
    private $request;

    /**
     * SupportController constructor.
     * @param Request $request
     * @param Support $support
     */
    public function __construct(Request $request, Support $support)
    {
        $this->support = $support;
        $this->request = $request;
    }


    /**
     * @param SupportRequest $request
     * @return JsonResponse
     */
    public function store(SupportRequest $request)
    {
        $this->support->create($request->all());
        return $this->sendResponse([], trans('api/messages.support.success'));
    }
}
