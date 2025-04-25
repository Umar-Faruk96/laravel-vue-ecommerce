<?php

namespace App\Http\Controllers\API;

use Exception;
use Illuminate\{Support\Str, Http\Request, Http\UploadedFile, Support\Facades\Storage, Support\Facades\URL};
use App\{Models\Api\Product, Http\Requests\ProductRequest, Http\Controllers\Controller, Http\Resources\ProductListResource, Http\Resources\ProductResource};

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');
        $sortBy = $request->get('sort_by', 'updated_at');
        $sortTo = $request->get('sort_to', 'desc');

        $query = Product::query()->orderBy($sortBy, $sortTo);

        if ($search) {
            $query->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        }

        return ProductListResource::collection($query->paginate($perPage));
    }

    public function store(ProductRequest $request)
    {
        $productData = $request->validated();

        $productData['created_by'] = request()->user()->id;
        $productData['updated_by'] = request()->user()->id;

        $productImage = $productData['image'] ?? null;

        // check if image is provided, then store it
        $productData = $this->checkProductImage($productImage, $productData);

        $product = Product::create($productData);

        return ProductResource::make($product);
    }

    public function show(Product $product)
    {
        return ProductResource::make($product);
    }

    public function update(ProductRequest $request, Product $product)
    {
        $productData = $request->validated();

        $productData['updated_by'] = request()->user()->id;

        $productImage = $productData['image'] ?? null;

        if ($productImage) {
            $productData = $this->checkProductImage($productImage, $productData);

            // delete old image
            if ($product->image) {
                Storage::deleteDirectory('images/' . dirname($product->image));
            }
        }

        $product->update($productData);

        return ProductResource::make($product);
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::deleteDirectory('images/' . dirname($product->image));
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }

    private function storeImage(UploadedFile $image)
    {
        $path = 'images/' . Str::random();

        if (!Storage::exists($path)) {
            Storage::makeDirectory($path);
        }

        if (!Storage::putFileAs($path, $image, $image->getClientOriginalName())) {
            throw new Exception('Failed to store image');
        }

        return $path . '/' . $image->getClientOriginalName();
    }

    public function checkProductImage(UploadedFile $image, array $productData)
    {
        if ($image) {
            $relativePath = $this->storeImage(image: $image);

            $productData['image'] = URL::to(Storage::url($relativePath) /* add /storage in the URL */); // make URL absolute

            $productData['image_mime'] = $image->getClientMimeType();

            $productData['image_size'] = $image->getSize();
        }

        return $productData;
    }
}
