<?php

namespace App\Services;

use App\Exceptions\NotFoundException;
use App\HasUploadImage;
use App\Models\Blog;
use Illuminate\Support\Facades\Auth;

class BlogService
{
    use HasUploadImage;

    public function store($validated)
    {
        $image = $this->saveImage($validated['image'], 'blog_image');

        $blog = Blog::create([
            'title' => $validated['image'],
            'description' => $validated['description'],
            'author_id' => Auth::user()->id,
            'image' => $image,
        ]);

        return $blog;
    }

    public function getComments($id)
    {
        $blog = Blog::find($id);

        if (!$blog) throw new NotFoundException();

        return $blog->comments();
    }
}
