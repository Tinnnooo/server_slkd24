<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'image' => $this->image,
            'tags' => $this->tags,
            'author' => [
                'name' => $this->author->name,
                'email' => $this->author->email,
                'phone_number' => $this->author->phone_number,
                'profile_picture' => $this->author->profile_picture,
            ],
            'comments' => $this->comments,
            'created_at' => $this->created_at,
        ];
    }
}
