<?php

namespace App\Repositories\Product;

use Illuminate\Http\Request;

interface ProductRepositoryInterface
{
    public function index(Request $request);

    public function store($validatedData);

    public function show($id);

    public function update($validatedData, $id);

    public function destroy($id);

    public function productList(Request $request);

    public function productListRandom(Request $request, $id);
}
