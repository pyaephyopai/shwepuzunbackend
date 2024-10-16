<?php

namespace App\Repositories\Category;

use Illuminate\Http\Request;

interface CategoryRepositoryInterface
{
    public function index(Request $request);

    public function store($validatedData);

    public function show($id);

    public function update($validatedData, $id);

    public function destroy($id);

    public function categoryList($request);
}
