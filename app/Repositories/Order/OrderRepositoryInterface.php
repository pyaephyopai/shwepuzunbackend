<?php

namespace App\Repositories\Order;

use Illuminate\Http\Request;

interface OrderRepositoryInterface
{
    public function index(Request $request);

    public function store($validatedData);

    public function show($id);

    public function update($request, $id);

    public function changeStatus($request, $id);

    public function userOrders($request);
}
