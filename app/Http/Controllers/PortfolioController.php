<?php

namespace App\Http\Controllers;

use App\Exceptions\NotFoundException;
use App\Http\Resources\PortfolioCollection;
use App\Http\Resources\PortfolioResource;
use App\Models\Portfolio;
use App\Traits\HasResponseHttp;
use App\Traits\HasUploadImage;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    use HasResponseHttp, HasUploadImage;

    public function get()
    {
        return $this->success(['data' => new PortfolioCollection(Portfolio::all())]);
    }

    public function create(CreateBlogRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $data = $this->blogService->store($validated);

        return $this->success(['data' => new BlogResource($data)]);
    }

    public function update(Request $request, int $id)
    {
        $portfolio = Portfolio::find($id);

        $data = $request->all();

        if (!$portfolio) throw new NotFoundException();

        if ($request->hasFile('image')) {
            $this->deleteImage($portfolio->image);

            $newImage = $this->saveImage($request['image'], 'portfolio_images');

            $data['image'] = $newImage;
        }

        $portfolio->update($data);
        $portfolio->save();

        return $this->success(['message' => 'Portfolio updated successfully', 'data' => new PortfolioResource($portfolio)]);
    }

    public function delete(int $id)
    {
        $portfolio = Portfolio::find($id);

        if (!$portfolio) throw new NotFoundException();

        $portfolio->delete();

        return $this->success(['message' => 'Portfolio deleted successfully']);
    }
}
