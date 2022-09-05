<?php

namespace App\Http\Controllers\Api\General;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\CityResource;
use App\Http\Resources\CountryResource;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getAllCountriesByCitites()
    {
        $cityTitle = $this->request->title;
        $cities = City::select('id', 'country_id', 'title', 'en_title', 'lat', 'long')
            ->with('country:id,iso_code3')
            ->when(!empty($cityTitle), function ($query) use ($cityTitle) {
                return $query->where('title', 'like', '%' . $cityTitle . '%')->orWhere('en_title', 'like', '%' . $cityTitle . '%');
            })
            ->orderBy('country_id')
            ->orderBy('title')
            ->get();

        return $this->sendResponse(CityResource::collection($cities));
    }
}
