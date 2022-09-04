<?php

namespace App\Http\Controllers\Site;

use App\Models\Ads;
use App\Models\AdsCategory;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Country;

/**
 * Class HomeController
 * @package App\Http\Controllers\Site
 */
class HomeController extends Controller
{

    /**
     * @var Ads
     */
    protected $ads;
    /**
     * @var BlogCategory
     */
    protected $blogCategory;
    /**
     * @var AdsCategory
     */
    protected $adsCategory;
    /**
     * @var Country
     */
    protected $country;
    /**
     * @var Blog
     */
    protected $blog;

    /**
     * HomeController constructor.
     * @param Ads $ads
     * @param BlogCategory $blogCategory
     * @param AdsCategory $adsCategory
     * @param Country $country
     * @param Blog $blog
     */
    public function __construct(
        Ads $ads,
        BlogCategory $blogCategory,
        AdsCategory $adsCategory,
        Country $country,
        Blog $blog
    )
    {
        $this->ads = $ads;
        $this->blogCategory = $blogCategory;
        $this->adsCategory = $adsCategory;
        $this->country = $country;
        $this->blog = $blog;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getHomePage()
    {
        $topLikeBlogs = $this->blog->getTopLikes(6, ['id', 'title'], ['contentImages']);
        $topViewAds = $this->ads->getTopViews(6);
        $cities = $this->country->wherePhoneCode(44)->first()->cities;
        $adsFavoriteCategories = $this->adsCategory->favorite()->orderBy('order', 'desc')->take(20);
        $adsHomeCategories = $this->adsCategory->home()->orderBy('order', 'desc')->take(20);
        $adsCategories = $this->adsCategory->orderByPagination();
        return view('v1.site.pages.index', compact(
            'topLikeBlogs',
            'topViewAds',
            'cities',
            'adsFavoriteCategories',
            'adsHomeCategories',
            'adsCategories')
        );
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAboutUs()
    {
        return view('v1.site.pages.other.about-us');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getFaq()
    {
        $faqs = trans('faq');
        return view('v1.site.pages.other.faq', compact('faqs'));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getTerms()
    {
        return view('v1.site.pages.other.terms');
    }
}
