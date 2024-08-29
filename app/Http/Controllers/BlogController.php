<?php

namespace App\Http\Controllers;

use App\HasResponseHttp;
use App\Http\Resources\BlogCommentCollection;
use App\Services\BlogService;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    use HasResponseHttp;

    protected $blogService;

    public function __construct(BlogService $blogService)
    {
        $this->blogService = $blogService;
    }

    public function getComments($id)
    {
        return $this->success(['data' => new BlogCommentCollection($this->blogService->getComments($id))]);
    }
}
