<?php

namespace App\Http\Controllers\Site;

use App\Models\Ads;
use App\Models\Blog;

;

/**
 * Class AjaxSearchController
 * @package App\Http\Controllers\Site
 */
class AjaxSearchController extends Controller
{

    /**
     * @var Ads
     */
    private $ads;

    /**
     * @var Blog
     */
    private $blog;

    /**
     * AjaxSearchController constructor.
     * @param Ads $ads
     * @param Blog $blog
     */
    public function __construct(Ads $ads, Blog $blog)
    {
        $this->ads = $ads;
        $this->blog = $blog;
    }

    /**
     * @param string $modelType define the type of model for searching on it
     * @param string $searchString define search string for search
     *
     * @return mixed
     */
    public function csearch($modelType = '', $searchString = '')
    {
        $method = $modelType . 'Search';

        if (!method_exists($this, $method)) {
            $data = [
                'status' => 'fault',
                'message' => 'ورود داده نامعتبر!',
                'data' => [],
                'code' => 400
            ];
            return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
        }

        return $this->$method($searchString);
    }

    /**
     * @param string $searchString
     * @return string
     */
    private function adsSearch(string $searchString)
    {
        $result = $this->ads->select('id', 'title')
            ->where('title', 'like', '%' . $searchString . '%')
            ->orderby('id', 'desc')
            ->take(20)
            ->get();

        return $this->makeAdsHtml(
            $result,
            'ads.show'
        );
    }

    /**
     * @param string $searchString
     * @return string
     */
    private function blogSearch(string $searchString)
    {
        $result = $this->blog->select('id', 'title')
            ->where('title', 'like', '%' . $searchString . '%')
            ->orderby('id', 'desc')
            ->take(20)
            ->get();

        return $this->makeBlogHtml(
            $result,
            'blog.show'
        );
    }

    /**
     * @param $searchResult
     * @param string $routeName
     * @return string
     */
    private function makeBlogHtml($searchResult, $routeName = '')
    {
        if (empty($searchResult->count())) {
            $data =
                '<li class="search_result_class"><a class="search_result_class">' .
                '<p style="font-size:0.8em;">اطلاعاتی یافت نشد!</p>' .
                '</a></li>';

            return $data;
        }

        $data = '';

        foreach ($searchResult as $row) {
            $data .=
                '<li class="search_result_class"><a style="padding: 0; margin-bottom: 5px;" href=' . route($routeName, [$row->id, $row->urlTitle]) . '>';
            $image = $row->logo['small'];
            $data .=
                '<div style="font-size:0.8em;">' .
                '<img src=' . $image . '>'
                . str_limit(html_entity_decode(strip_tags($row->title)), 10) .
                '</div>' .
                '</a></li>';
        }
        return $data;
    }

    /**
     * @param $searchResult
     * @param string $routeName
     * @return string
     */
    private function makeAdsHtml($searchResult, $routeName = '')
    {
        if (empty($searchResult->count())) {
            $data =
                '<li class="search_result_class"><a class="search_result_class">' .
                '<p style="font-size:0.8em;">اطلاعاتی یافت نشد!</p>' .
                '</a></li>';
            return $data;
        }

        $data = '';

        foreach ($searchResult as $row) {
            $data .=
                '<li class="search_result_class"><a style="padding: 0; margin-bottom: 2px;" href=' . route($routeName, $row->id) . '>';
            $image = $row->logo['small'];
            $data .=
                '<div style="font-size:0.8em;">' .
                '<img src=' . $image . '>'
                . str_limit(html_entity_decode(strip_tags($row->title)), 10) .
                '</div>' .
                '</a></li>';
        }

        return $data;
    }
}
