<?php

namespace App\Http\Controllers\Api\Comment;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Http\Controllers\Api\Controller;
use Illuminate\Http\Request;

/**
 * Class CommentController
 * @package App\Http\Controllers\Api\Comment
 */
class CommentController extends Controller
{
    /**
     * @var Comment
     */
    private $comment;
    /**
     * @var Request
     */
    protected $request;

    /**
     * CommentController constructor.
     * @param Request $request
     * @param Comment $comment
     */
    public function __construct(Request $request, Comment $comment)
    {
        $this->comment = $comment;
        $this->request = $request;
    }

    public function index($type, $id)
    {
        $perPage = $this->getPerPage();
        $this->validateRequest(['type' => $type, 'id' => $id    ], [
            'type' => 'required|in:ads,blog',
            'id' => 'required|exists:blogs,id,deleted_at,NULL',
        ]);

        $methodName = 'get' . ucfirst($this->request->type) . 'Comments';
        $comments = $this->comment
            ->$methodName()->published()
            ->where('commentable_id', $id)
            ->orderByDesc('published_at')
            ->paginate($perPage);

        return $this->sendResponse([
            'comments' => CommentResource::collection($comments),
            'pagination' => [
                "totalItems" => $comments->total(),
                "perPage" => $comments->perPage(),
                "nextPageUrl" => $comments->nextPageUrl(),
                "previousPageUrl" => $comments->previousPageUrl(),
                "lastPageUrl" => $comments->url($comments->lastPage())
            ]
        ]);
    }
}
