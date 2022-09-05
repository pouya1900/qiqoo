<?php

namespace App\Http\Controllers\Site;
use App\Models\FileType;
use App\Models\Blog;
use App\Models\BlogCategory;

class BlogController extends Controller
{
    protected $blog;
    protected $blogCategory;

    public function __construct(Blog $blog, BlogCategory $blogCategory)
    {
        $this->blog = $blog;
        $this->blogCategory = $blogCategory;
    }

    public function index()
    {
        $blogs = $this->blog->orderByPagination();
        $blogCategories = $this->blogCategory->all();
        $newBlogs = $this->blog->getNewRecords(5);
        $topLikes = $this->blog->getTopLikes(5);
        $topViews = $this->blog->getTopLikes(5);
        $topComments = $this->blog->getTopComments(5);
        return view('v1.site.pages.blog.index',
            compact('blogs', 'blogCategories', 'newBlogs', 'topLikes', 'topViews', 'topComments')
        );
    }

    public function show(Blog $blog, $title)
    {
//        $blogs = $this->blog->fetchAll();
//        $categories = array();
//
//        foreach ($all_blogs  as $row) {
//            $categories [] = $row->category;
//        }
//        $categories = collect($categories)->unique()->values()->all();

        $blogCategories = $this->blogCategory->all();
        $relatedBlogs = collect([]); //$blog->category->blogs()->where('id', '<>', $blog->id)->take(3);
        $newBlogs = $this->blog->getNewRecords(5);
        $topLikes = $this->blog->getTopLikes(5);
        $topViews = $this->blog->getTopLikes(5);
        $topComments = $this->blog->getTopComments(5);
        return view('v1.site.pages.blog.show',
            compact('blog', 'blogCategories', 'relatedBlogs', 'newBlogs', 'topLikes', 'topViews', 'topComments')
        );
    }

    public function getByCategory(BlogCategory $blogCategory, $title)
    {
        $blogs = $blogCategory/*->with('blogs')*/->blogs()->paginate(12);
        return view('v1.site.pages.blog.category-blog', compact('blogCategory', 'blogs'));
    }
}
