<?php

namespace App\Services;

use App\Exceptions\NotFoundException;
use App\Exceptions\ServerBusyException;
use App\HasUploadImage;
use App\Models\Blog;
use App\Models\BlogComment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BlogService
{
    use HasUploadImage;

    public function store(array $validated): Blog
    {
        $image = $this->saveImage($validated['image'], 'blog_image');

        DB::beginTransaction();

        try {
            $blog = Blog::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'tags' => $validated['tags'],
                'author_id' => Auth::user()->id,
                'image' => $image,
            ]);

            DB::commit();

            return $blog;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new ServerBusyException();
        }
    }

    public function getComments(int $id): BlogComment
    {
        $blog = Blog::find($id);

        if (!$blog) throw new NotFoundException();

        return $blog->comments();
    }
}
