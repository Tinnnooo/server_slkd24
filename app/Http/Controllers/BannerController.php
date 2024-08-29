<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBannerRequest;
use App\Http\Resources\BannerCollection;
use App\Http\Resources\BannerResource;
use App\Models\Banner;
use App\Traits\HasResponseHttp;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    use HasResponseHttp;

    public function get()
    {
        return $this->success(['data' => new BannerCollection(Banner::all())]);
    }

    public function create(CreateBannerRequest $request)
    {
        $validated = $request->validated();

        $banner = Banner::create($validated);

        return $this->success(['message' => 'Banner created successfully', 'data' => new BannerResource($banner)]);
    }
}
