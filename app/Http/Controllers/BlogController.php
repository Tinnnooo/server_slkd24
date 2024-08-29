<?php

namespace App\Http\Controllers;

use App\Exceptions\NotFoundException;
use App\HasResponseHttp;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\CreateBlogRequest;
use App\Http\Requests\CreateCommentRequest;
use App\Http\Resources\BlogCommentCollection;
use App\Http\Resources\BlogCommentResource;
use App\Http\Resources\BlogResource;
use App\Models\BlogComment;
use App\Services\BlogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    use HasResponseHttp;

    protected $blogService;

    public function __construct(BlogService $blogService)
    {
        $this->blogService = $blogService;
    }

    public function create(CreateBlogRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $data = $this->blogService->store($validated);

        return $this->success(['data' => new BlogResource($data)]);
    }

    public function getComments(int $id): JsonResponse
    {
        return $this->success(['data' => new BlogCommentCollection($this->blogService->getComments($id))]);
    }

    public function createComment(CommentRequest $request, int $id)
    {
        $validated = $request->validated();

        $data = $this->blogService->storeComment($validated, $id);

        return $this->success(['message' => 'Comment created successfully', 'data' => new BlogCommentResource($data)]);
    }

    public function updateComment(Request $request, int $id)
    {
        $comment = BlogComment::find($id);

        if (!$comment) throw new NotFoundException();

        $comment->update($request->all());
        $comment->save();

        return $this->success(['message' => 'Comment updated successfully', 'data' => new BlogCommentResource($comment)]);
    }

    public function deleteComment(int $id)
    {
        $comment = BlogComment::find($id);

        if (!$comment) throw new NotFoundException();

        $comment->delete();

        return $this->success(['message' => 'Comment deleted successfully']);
    }
}
