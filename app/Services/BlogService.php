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

    public function storeComment(array $validated, int $id): BlogComment
    {
        DB::beginTransaction();

        try {
            $blogComment = BlogComment::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'subject' => $validated['subject'],
                'website' => $validated['website'],
                'comment' => $validated['comment'],
                'blog_id' => $id,
            ]);

            DB::commit();

            return $blogComment;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new ServerBusyException();
        }
    }

    public function getComments(int $id)
    {
        $blog = Blog::find($id);

        if (!$blog) throw new NotFoundException();

        return $blog->comments;
    }
}
