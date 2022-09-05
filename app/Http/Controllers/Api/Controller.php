<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\AppException;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Traits\ResponseUtilsTrait;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
	
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use ResponseUtilsTrait;

    public function fallback()
    {
        return $this->sendError(trans('api/messages.fallback'), config(    'responseCode.notFound'));
    }

    protected function validateRequest(array $requestData, array $rules)
    {
        $validator = Validator::make($requestData, $rules);

        if ($validator->fails()) {
            throw new AppException($validator->errors()->first(), config('responseCode.validationFail'));
        }
    }

    protected function getPerPage()
    {
        $this->validateRequest($this->request->input(), [
            'per_page' => 'nullable|integer|between:1,100'
        ]);

        return $perPage = empty($this->request->perPage) ? config('global.api.perPage') : $this->request->perPage;
    }

    protected function checkBookmarkStatus($ads, $user = null)
    {
        if(empty($user)){
            return $ads;
        }

        if(isset($ads->id)){
            $ads->isBookmark = $user->bookmarks->contains($ads->id) ? 1 : 0;
            return $ads;
        }

        foreach ($ads as $selected) {
            $selected->isBookmark = $user->bookmarks->contains($selected->id) ? 1 : 0;
        }

        return $ads;
    }
}
