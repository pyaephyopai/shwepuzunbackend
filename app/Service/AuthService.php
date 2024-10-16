<?php

namespace App\Service;

use App\Http\Resources\AuthResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthService
{

    public function login($request)
    {
        $credential = $request->only('email', 'password');

        $startTime = microtime(true);

        $user = User::where('email', 'LIKE BINARY', $request['email'])->first();

        if (!$user) {

            return response()->error($request, null, 'Please Check Your Email Again', 400, $startTime, 1);
        }

        if (!$user->hasVerifiedEmail()) {

            return response()->error($request, null, 'Your email address is not verified.', 400, $startTime, 1);
        }

        if ($user && Hash::check($credential['password'], $user['password'])) {

            $user['token'] = $user->createToken('project')->plainTextToken;

            $user['role'] = optional($user->roles->first())->name;

            $user['permission'] = $user->getPermissionsViaRoles()->pluck('name');

            $result = new AuthResource($user);

            return response()->success(request(), $result, 'User Login Successfully', 200, $startTime, 1);
        } else {
            return response()->error($request, null, ' Please Check your Password again .', 400, $startTime, 1);
        }
    }

    public function register($request)
    {
        $startTime = microtime(true);

        try {
            DB::transaction(function () use ($request) {

                $user = User::create([
                    'name' => $request['name'],
                    'email' => $request['email'],
                    'phone_number' => $request['phone_number'],
                    'gender' => $request['gender'],
                    'address' => $request['address'],
                    'region' => $request['region'],
                    'role_id' => 2,
                    'password' => Hash::make($request['password']),
                ]);

                $user->sendEmailVerificationNotification();

                $user->assignRole($user->role_id);
            });

            return response()->success(request(), true, 'User registered successfully . Please Check your mail to verify your emil address.', 201, $startTime, 1);
        } catch (\Exception $e) {
            return response()->error(request(), null, 'Registration failed. Please try again.', 500, $startTime);
        }
    }

    public function logout($request)
    {
        $startTime = microtime(true);

        $request->user()->currentAccessToken()->delete();

        return response()->success($request, null, "User Logout Successfully", 200, $startTime, 1);
    }
}
