<?php

namespace App\Services;

use App\Exceptions\NotFoundException;
use App\Models\Blog;

class BlogService
{
    public function getComments($id)
    {
        $blog = Blog::find($id);

        if (!$blog) throw new NotFoundException();

        return $blog->comments();
    }
}
