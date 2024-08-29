<?php

namespace App\Http\Controllers;

use App\HasResponseHttp;
use App\Http\Requests\CreateBlogRequest;
use App\Http\Requests\CreateCommentRequest;
use App\Http\Resources\BlogCommentCollection;
use App\Http\Resources\BlogResource;
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

    public function createComment(CreateCommentRequest $request, int $id)
    {
        $validated = $request->validated();
    }
}
