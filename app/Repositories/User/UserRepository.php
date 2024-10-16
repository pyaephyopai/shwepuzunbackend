<?php

namespace App\Repositories\User;

use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    protected function limit(Request $request)
    {
        $limit = (int) $request->input('limit', Config::get('paginate.default_limit'));

        return $limit;
    }

    public function index(Request $request)
    {

        return User::orderBy('id', 'desc')->paginate($this->limit($request));
    }

    public function store($request)
    {

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'phone_number' => $request['phone_number'],
            'gender' => $request['gender'],
            'address' => $request['address'],
            'region' => $request['region'],
            'role_id' => $request['role_id'],
            'password' => Hash::make($request['password']),
            'email_verified_at' => Carbon::now(),
        ]);


        $user->assignRole((int) $user['role_id']);

        return $user;
    }

    public function show($request, $id)
    {
        $user = User::where('id', $id)->first();

        if (!$user) {
            throw new Exception('User not Found !l', 404);
        }

        return $user;
    }

    public function update($validatedData, $id)
    {

        $user = User::where('id', $id)->first();

        if (!$user) {
            throw new Exception('User not Found !l', 404);
        }

        $user->update($validatedData);

        $roleId = (int)$validatedData['role_id'];

        $user->syncRoles($roleId);

        $user->load('roles');

        return $user;
    }

    public function destory($id)
    {
        $user = User::where('id', $id)->first();

        if (!$user) {
            throw new Exception('User not Found!', 404);
        }
        return $user->delete();
    }

    public function userInformationUpdate(Request $request, $id)
    {
        $user = User::where('id', $id)->first();

        if (!$user) {
            throw new Exception('User not found.', 404);
        }

        if ($request->hasFile('image')) {
            $fileName = uniqid() . '-' . $request->image->getClientOriginalName();

            $request->image->storeAs('public/userProfileImages', $fileName);

            $user['image'] = $fileName;
        }

        return $user->update([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'gender' => $request->gender,
            'address' => $request->address,
            'region' => $request->region,

        ]);
    }

    public function userChangePassword($request)
    {

        $user = User::where('id', Auth::user()->id)->first();

        if (!$user) {
            throw new Exception('User not Found!');
        }
        if (Hash::check($request->old_password, $user->password)) {
            $newPassword = hash::make($request->new_password);

            $user->update([
                'password' => $newPassword
            ]);
        } else {
            throw new Exception('Your Password does not match!', 400);
        }

        return $user;
    }
}
