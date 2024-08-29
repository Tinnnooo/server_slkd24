<?php

namespace App\Services;

use App\Models\Blog;

class BlogService
{
    public function getComments($id)
    {
        $blog = Blog::findOrFail($id);

        return $blog->comments();
    }
}
