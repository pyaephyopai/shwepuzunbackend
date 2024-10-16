<?php

namespace App\Repositories\Category;

use App\Models\Category;
use App\Repositories\Category\CategoryRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class CategoryRepository implements CategoryRepositoryInterface
{

    protected function limit(Request $request)
    {
        $limit = (int) $request->input('limit', Config::get('paginate.default_limit'));

        return $limit;
    }

    public function index(Request $request)
    {
        return Category::orderBy('created_at', 'desc')->paginate($this->limit($request));
    }

    public function store($validatedData)
    {
        return Category::create($validatedData);
    }

    public function show($id)
    {

        $category = Category::where('id', $id)->first();

        if (!$category) {
            throw new Exception('Category not Found!.', 404);
        }

        return $category;
    }


    public function update($validatedData, $id)
    {
        $category =  Category::where('id', $id)->first();

        if (!$category) {
            throw new Exception('Category not Found!.', 404);
        }

        $category->update($validatedData);
    }

    public function destroy($id)
    {
        $category = Category::where('id', $id)->first();

        if (!$category) {
            throw new Exception('Category not Found!');
        }

        if ($category->products()->exists()) {
            throw new Exception('Category cannot be deleted because it has associated products', 400);
        }

        $category->delete($id);
    }

    public function categoryList($request)
    {
        return Category::get();
    }
}
