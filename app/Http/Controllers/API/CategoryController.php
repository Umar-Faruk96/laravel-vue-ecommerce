<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $sortBy = $request->input('sort_by', 'id');
        $sortOrder = $request->input('sort_order', 'asc');

        $categories = Category::orderBy($sortBy, $sortOrder)->latest()->get();

        return CategoryResource::collection($categories);
    }

    public function store(CategoryRequest $request)
    {
        $validated = $request->validated();
        $validated['created_by'] = $request->user()->id;
        $validated['updated_by'] = $request->user()->id;

        $category = Category::create($validated);

        return new CategoryResource($category);
    }
}
