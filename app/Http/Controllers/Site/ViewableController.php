<?php

namespace App\Http\Controllers\Site;

use App\Http\Requests\Site\CommentRequest;
use App\Models\Comment;
use App\Models\Ads;
use App\Models\Blog;

class ViewableController extends Controller
{
    protected $comment;
    protected $blog;
    protected $ads;

    public function __construct(Comment $comment, Blog $blog, Ads $ads)
    {
        $this->comment = $comment;
        $this->blog = $blog;
        $this->ads = $ads;
    }

    public function store(CommentRequest $request, $type, $id)
    {
        try{
            $validTypes = ['blog', 'ads'];

            if(!in_array($type, $validTypes)){
                throw new \Exception('نوع داده معتبر نمی باشد.');
            }
            $request->merge([
                'user_id' => !auth()->check() ? null : auth()->user()->id
            ]);

            $model = $type = strtolower($type) == 'ads' ? $this->ads->findOrFail($id) : $this->blog->findOrFail($id);
            $model->comments()->create($request->all());

            $msg = trans('messages.comment.success');
            return view('v1.site.pages.other.message', compact('msg'));
        }catch(\Exception $e) {
            $msg = trans('messages.comment.failed');
            return view('v1.site.pages.other.message', compact('msg'));
        }
    }
}
