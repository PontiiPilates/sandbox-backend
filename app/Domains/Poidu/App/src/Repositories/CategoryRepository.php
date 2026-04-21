<?php

namespace App\Domains\Poidu\App\src\Repositories;

use App\Domains\Poidu\App\src\Http\Resources\CategoryResource;
use App\Domains\Poidu\App\src\Models\Category;
use Illuminate\Http\Request;

final class CategoryRepository
{
    public function getCategories(Request $request)
    {
        $categories = Category::get();

        return CategoryResource::collection($categories);
    }
}
