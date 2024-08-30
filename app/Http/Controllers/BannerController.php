<?php

namespace App\Http\Controllers;

use App\Exceptions\NotFoundException;
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
        return $this->success(['banners' => new BannerCollection(Banner::all())]);
    }

    public function create(CreateBannerRequest $request)
    {
        $validated = $request->validated();

        $path = $this->saveImage($validated['image'], 'banner_images');

        $validated['image'] = $path;

        $banner = Banner::create($validated);

        return $this->success(['message' => 'Banner created successfully', 'data' => new BannerResource($banner)], 201);
    }

    public function update(Request $request, int $id)
    {
        $banner = Banner::find($id);

        $data = $request->all();

        if (!$banner) throw new NotFoundException();

        if ($request->hasFile('image')) {
            $this->deleteImage($banner->image);

            $newImage = $this->saveImage($request['image'], 'banner_images');

            $data['image'] = $newImage;
        }

        $banner->update($request->all());
        $banner->save();

        return $this->success(['message' => 'Banner updated successfully', 'data' => new BannerResource($banner)]);
    }

    public function delete(int $id)
    {
        $banner = Banner::find($id);

        if (!$banner) throw new NotFoundException();

        $banner->delete();

        return $this->success(['message' => 'Banner deleted successfully']);
    }
}
