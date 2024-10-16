<?php

namespace App\Repositories\User;

use Illuminate\Http\Request;

interface UserRepositoryInterface
{
    public function index(Request $request);

    public function store($request);

    public function show($request, $id);

    public function update($validatedData, $id);

    public function destory($id);

    public function userInformationUpdate(Request $request, $id);

    public function userChangePassword($request);
}
