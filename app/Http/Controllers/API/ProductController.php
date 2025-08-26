<?php

namespace App\Http\Controllers\API;

use Exception;
use Illuminate\{Http\JsonResponse,
    Http\Resources\Json\AnonymousResourceCollection,
    Support\Facades\DB,
    Support\Str,
    Http\Request,
    Http\UploadedFile,
    Support\Facades\Storage,
    Support\Facades\URL
};
use App\{Models\Api\Product,
    Http\Requests\ProductRequest,
    Http\Controllers\Controller,
    Http\Resources\ProductListResource,
    Http\Resources\ProductResource
};
use Illuminate\Database\Eloquent\Builder;

class ProductController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');
        $sortBy = $request->get('sort_by', 'created_at');
        $sortTo = $request->get('sort_to', 'desc');

        /* $query = Product::query()->orderBy($sortBy, $sortTo);

        if ($search) {
            $query->where('title', 'like', "%$search%")
                ->orWhere('description', 'like', "%$search%");
        } */

        // Advanced query with when method
        return ProductListResource::collection(Product::when($search, function (Builder $query, string $search) use ($sortBy) {
            $query->where('title', 'like', "%$search%")
                ->orWhere('description', 'like', "%$search%")
                ->orderByDesc($sortBy);
        }, function (Builder $query) use ($sortBy, $sortTo) {
            $query->orderBy($sortBy, $sortTo);
        })->paginate($perPage));

        // return ProductListResource::collection($query->paginate($perPage));
    }

    /**
     * @throws Exception
     */
    public function storeV0(ProductRequest $request): ProductResource
    {
        $productData = $request->validated();

        $productData['created_by'] = request()->user()->id;
        $productData['updated_by'] = request()->user()->id;

        $productImage = $productData['image'] ?? null;

        if ($productImage && $productImage instanceof UploadedFile) {
            $productData = $this->checkProductImage($productImage, $productData);
        }


        $product = Product::create($productData);

        return ProductResource::make($product);
    }

    public function store(ProductRequest $request): ProductResource
    {
        $productData = $request->validated();

        $productData['created_by'] = request()->user()->id;
        $productData['updated_by'] = request()->user()->id;
        $product = Product::create($productData);

        $productImages = $productData['images'] ?? [];
        try {
            $this->saveImages($productImages, $product);
        } catch (Exception $e) {
            $product->delete();
            throw $e;
        }

        $product->load('images');
        return ProductResource::make($product);
    }

    public function show(Product $product): ProductResource
    {
        $product->load('images');
        return ProductResource::make($product);
    }

    /**
     * @throws Exception
     */
    public function updateV0(ProductRequest $request, Product $product): ProductResource
    {
        $productData = $request->validated();

        $productData['updated_by'] = request()->user()->id;

        $productImage = $productData['image'] ?? null;

        if ($productImage && $productImage instanceof UploadedFile) {
            // delete old image
            if ($product->image) {
                Storage::deleteDirectory('images/' . basename(dirname($product->image), '/'));
            }

            $productData = $this->checkProductImage($productImage, $productData);
        }

        $product->update($productData);

        return ProductResource::make($product);
    }

    public function update(ProductRequest $request, Product $product): ProductResource
    {
        $productData = $request->validated();

        $productData['updated_by'] = request()->user()->id;

        $productImages = $productData['images'] ?? [];
        $deletedImages = $productData['deleted_images'] ?? [];

        DB::beginTransaction();
        try {
            $product->update($productData);

            $this->saveImages($productImages, $product);

            if (count($deletedImages) > 0)
                $this->deleteImages($deletedImages, $product);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
        DB::commit();

        $product->load('images');
        return ProductResource::make($product);
    }

    public function destroy(Product $product): JsonResponse
    {
        if ($product->image) {
            Storage::deleteDirectory('images/' . dirname($product->image));
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }

    public function saveImages(array $images, Product $product): void
    {
        try {
            foreach ($images as $key => $image) {
                if ($image instanceof UploadedFile) {
                    $directory = 'products/images/' . $key . '_' . time() . '_' . $product->id . '_' . $product->title;
                    $filename = $image->getClientOriginalName();
                    if (!Storage::putFileAs($directory, $image, $filename)) {
                        throw new Exception('Failed to store image: ' . $filename);
                    }
                    $relativePath = $directory . '/' . $filename;
                    $product->images()->create([
                        'product_id' => $product->id,
                        'path' => $relativePath,
                        'url' => URL::to(Storage::url($relativePath)),
                        'mime' => $image->getClientMimeType(),
                        'size' => $image->getSize(),
                        'position' => $key + 1
                    ]);
                }
            }
        } catch (Exception $e) {
            throw new Exception('Oops! Something wrong happened while uploading image. Message: ' . $e->getMessage());
        }
    }

    private function deleteImages(array $imageIds, Product $product): void
    {
        foreach ($product->images as $image) {
            if (in_array($image->id, $imageIds) && $image->path) {
                Storage::delete($image->path);
                $image->delete();
            }
        }
    }

    /**
     * @throws Exception
     */
    public function checkProductImage(UploadedFile $image, array $productData): array
    {
        if ($image) {
            $relativePath = $this->storeImage(image: $image);

            $productData['image'] = URL::to(Storage::url($relativePath) /* add storage in the URL */); // make URL absolute

            $productData['image_mime'] = $image->getClientMimeType();

            $productData['image_size'] = $image->getSize();
        }

        return $productData;
    }

    /**
     * @throws Exception
     */
    private function storeImage(UploadedFile $image): string
    {
        $path = 'images/' . Str::random();

        /* if (!Storage::exists($path)) {
            Storage::makeDirectory($path);
        } */

        if (!Storage::putFileAs($path, $image, $image->getClientOriginalName())) {
            throw new Exception('Failed to store image');
        }

        return $path . '/' . $image->getClientOriginalName();
    }
}
