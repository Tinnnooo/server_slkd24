<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBannerRequest;
use App\Http\Resources\BannerCollection;
use App\Http\Resources\BannerResource;
use App\Models\Banner;
use App\Traits\HasResponseHttp;
use App\Traits\HasUploadImage;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    use HasResponseHttp, HasUploadImage;

    public function get()
    {
        return $this->success(['data' => new BannerCollection(Banner::all())]);
    }

    public function create(CreateBannerRequest $request)
    {
        $validated = $request->validated();

        $path = $this->saveImage($validated['image'], 'banner_images');

        $validated['image'] = $path;

        $banner = Banner::create($validated);

        return $this->success(['message' => 'Banner created successfully', 'data' => new BannerResource($banner)]);
    }
}
