<?php

namespace App\Http\Controllers\Api\Blog;
use App\Http\Requests\Api\CommentRequest;
use App\Http\Resources\BlogCategoryResource;
use App\Http\Resources\BlogDetailResource;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Http\Controllers\Api\Controller;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    protected $blog;
    protected $blogCategory;
    protected $request;

    public function __construct(Request $request, Blog $blog, BlogCategory $blogCategory)
    {
        $this->blog = $blog;
        $this->blogCategory = $blogCategory;
        $this->request = $request;
    }

    public function index()
    {
        $searchString = $this->request->sc;
        $perPage = $this->request->perPage;
        $highlights = $this->request->highlights;

        $blogs = $this->blog
            ->query()->when(!empty($searchString), function($query) use($searchString){
                return $query->where('title', 'Like', '%' . $searchString . '%');
            })->publish()
        ->orderByPagination(!empty($perPage) ? $perPage : config('global.api.perPage'));



//        $newBlogs = $this->blog->getNewRecords(5);
//        $topLikes = $this->blog->getTopLikes(5);
//        $topViews = $this->blog->getTopLikes(5);
//        $topComments = $this->blog->getTopComments(5);


	    $response=[
		    'blogs' => BlogResource::collection($blogs),
		    'pagination' => [
			    "totalItems" => $blogs->total(),
			    "perPage" => $blogs->perPage(),
			    "nextPageUrl" => $blogs->nextPageUrl(),
			    "previousPageUrl" => $blogs->previousPageUrl(),
			    "lastPageUrl" => $blogs->url($blogs->lastPage())
		    ]
	    ];

	    if ($highlights) {

		    $highlights_blog = $this->blog
			    ->query()->
				    where('slider_blog', '=', 1)
			    ->orderByPagination(!empty($perPage) ? $perPage : config('global.api.perPage'));


		    $favorite_category=$this->blogCategory->
			    query()->
			    where('is_favorite', '=', 1)
			    ->get();


		    $response["sliderBlogs"]=BlogResource::collection($highlights_blog);
		    $response["favoriteCategory"]=BlogCategoryResource::collection($favorite_category);

	    }


        return $this->sendResponse($response);
    }

    public function show(Blog $blog)
    {
        $blog->publishedComments = $blog->publishedComments()
            ->orderBy('published_at', 'desc')
            ->take(10)
            ->get();
        
        return $this->sendResponse(new BlogDetailResource($blog));
    }

    public function getCategories()
    {
        $perPage = $this->getPerPage();
        $blogCategories = $this->blogCategory
            ->select('*')
            ->when(!empty($this->request->is_favorite), function($query){
                return $query->whereIsFavorite(true);
            })
            ->orderByDesc('created_at')
            ->paginate($perPage);
        return $this->sendResponse([
            'categories' => BlogCategoryResource::collection($blogCategories),
            'pagination' => [
                "totalItems" => $blogCategories->total(),
                "perPage" => $blogCategories->perPage(),
                "nextPageUrl" => $blogCategories->nextPageUrl(),
                "previousPageUrl" => $blogCategories->previousPageUrl(),
                "lastPageUrl" => $blogCategories->url($blogCategories->lastPage())
        ]]);
    }

    public function getByCategory(BlogCategory $blogCategory)
    {
        $searchString = $this->request->sc;
        $blogs = $blogCategory->blogs()
            ->when(!empty($searchString), function($query) use($searchString){
                return $query->where('title', 'Like', '%' . $searchString . '%');
            })
            ->orderByPagination(config('global.api.perPage'));

        return $this->sendResponse([
            'blogs' => BlogResource::collection($blogs),
            'pagination' => [
                "totalItems" => $blogs->total(),
                "perPage" => $blogs->perPage(),
                "nextPageUrl" => $blogs->nextPageUrl(),
                "previousPageUrl" => $blogs->previousPageUrl(),
                "lastPageUrl" => $blogs->url($blogs->lastPage())
            ]
        ]);
    }

    public function addComment(CommentRequest $request, Blog $blog)
    {
        $blog->comments()->create($request->all());
        return $this->sendResponse();
    }


}
