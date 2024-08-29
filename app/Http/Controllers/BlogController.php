<?php

namespace App\Http\Controllers;

use App\Exceptions\NotFoundException;
use App\Traits\HasResponseHttp;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\CreateBlogRequest;
use App\Http\Requests\CreateCommentRequest;
use App\Http\Resources\BlogCollection;
use App\Http\Resources\BlogCommentCollection;
use App\Http\Resources\BlogCommentResource;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use App\Models\BlogComment;
use App\Services\BlogService;
use App\Traits\HasUploadImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    use HasResponseHttp, HasUploadImage;

    protected $blogService;

    public function __construct(BlogService $blogService)
    {
        $this->blogService = $blogService;
    }

    public function get()
    {
        return $this->success(['data' => new BlogCollection(Blog::all())]);
    }

    public function update(Request $request, int $id)
    {
        $blog = Blog::find($id);

        $data = $request->all();

        if (!$blog) throw new NotFoundException();

        if ($request->hasFile('image')) {
            $this->deleteImage($blog->image);

            $newImage = $this->saveImage($request['image'], 'blog_images');

            $data['image'] = $newImage;
        }

        $blog->update($data);
        $blog->save();

        return $this->success(['message' => 'Blog updated successfully', 'data' => new BlogResource($blog)]);
    }

    public function delete(int $id)
    {
        $blog = Blog::find($id);

        if (!$blog) throw new NotFoundException();

        $blog->delete();

        return $this->success(['message' => 'Blog deleted successfully']);
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
