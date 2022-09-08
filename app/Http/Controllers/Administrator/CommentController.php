<?php

namespace App\Http\Controllers\Administrator;

use App\Events\SeenAdminEvent;
use App\Models\Comment;
use App\Models\Ads;
use App\Models\Blog;

class CommentController extends Controller
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

    public function index()
    {
        $sub_sequence = ['id' => 0, 'title' => 'مدیریت دیدگاه ها'];
        $comments = $this->comment->OrderByPagination();
        return view('v1.admin.pages.comment.index', compact('comments', 'sub_sequence'));
    }

    public function trashed()
    {
        $sub_sequence = ['id' => 1, 'title' => 'سطل زباله'];
        $comments =  $this->comment->fetchOnlyTrashedByPagination('', '', '*', 100, 'deleted_at', 'desc');
        return view('v1.admin.pages.comment.index', compact('comments', 'sub_sequence'));
    }

    public function show(Comment $comment)
    {
        if(empty($comment->seen_at)){
            event(new SeenAdminEvent($comment, auth()->user()->id));
        }

        return view('v1.admin.pages.comment.show', compact('comment'));
    }

    public function doDelete(Comment $comment)
    {
        try {
            $comment->delete();
            session()->flash('notifications', ['message' => 'عملیات با موفقیت انجام شد', 'alert_type' => 'success']);
            $data = [
                'status' => 'success',
                'message' => 'عملیات با موفقیت انجام شد',
                'data' => ['alireza'],
                'code' => 200
            ];
            return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            session()->flash('notifications', ['message' => 'خطا در انجام عملیات: لطفا با مدیر سایت تماس بگیرید.', 'alert_type' => 'warning']);
            $data = [
                'status' => 'fault',
                'message' => 'خطا: ' . $e->getMessage(),
                'data' => [],
                'code' => 400
            ];
            return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
        }
    }

    public function reversePublishState(Comment $comment)
    {
        try {
            $data = [
                'published_admin_id' => auth()->user()->id,
                'published_at' => now()
            ];

            if(!empty($comment->published_at)){
                $data = [
                    'published_admin_id' => null,
                    'published_at' => null
                ];
            }

            $comment->update($data);

            session()->flash('notifications', ['message' => 'عملیات با موفقیت انجام شد', 'alert_type' => 'success']);
            $data = [
                'status' => 'success',
                'message' => 'عملیات با موفقیت انجام شد',
                'data' => ['alireza'],
                'code' => 200
            ];
            return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            session()->flash('notifications', ['message' => 'خطا در انجام عملیات: لطفا با مدیر سایت تماس بگیرید.', 'alert_type' => 'warning']);
            $data = [
                'status' => 'fault',
                'message' => 'خطا: ' . $e->getMessage(),
                'data' => [],
                'code' => 400
            ];
            return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
