<?php

namespace App\Repositories\Blog;

use Illuminate\Http\Request;

interface BlogRepositoryInterface
{

    public function index(Request $request);

    public function store($request);

    public function show($id);

    public function update($request, $id);

    public function destroy($id);

    public function blogListRandom($id);
}
