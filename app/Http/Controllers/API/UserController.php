<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserChangePasswordRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Repositories\User\UserRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;

        $this->middleware('permission:getUserListing', ['only' => ['getUserListing']]);

        $this->middleware('permission:userStore', ['only' => ['store']]);

        $this->middleware('permission:userUpdate', ['only' => ['update']]);

        $this->middleware('permission:userShow', ['only' => ['show']]);

        $this->middleware('permission:userDelete', ['only' => ['destory']]);

        $this->middleware('permission:userInfoUpdate', ['only' => ['userInformationUpdate']]);
    }

    public function index(Request $request)
    {
        try {
            $startTime = microtime(true);

            $data = $this->userRepository->index($request);

            $result = UserResource::collection($data);

            return response()->paginate($request, $result, 'User Retrived Successfully', 200, $startTime);
        } catch (Exception $e) {

            Log::channel('web_daily_error')->error('Error Retriving Roles' . $e->getMessage());

            return response()->error($request, null, $e->getMessage(), 500, $startTime);
        }
    }

    public function store(UserStoreRequest $request)
    {
        try {
            $startTime = microtime(true);

            $this->userRepository->store($request);

            return response()->success($request, null, 'User Created Successfully', 201, $startTime, 1);
        } catch (Exception $e) {

            Log::channel('web_daily_error')->error('Error Creating User.' . $e->getMessage());

            return response()->error($request, null, $e->getMessage(), 500, $startTime);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $startTime = microtime(true);

            $data = $this->userRepository->show($request, $id);

            $result = new UserResource($data);

            return response()->success($request, $result, 'User Retrieved Successfully', 200, $startTime, 1);
        } catch (Exception $e) {

            Log::channel('web_daily_error')->error('Error Retrieving User.' . $e->getMessage());

            return response()->error($request, null, $e->getMessage(), 500, $startTime);
        }
    }

    public function update(UserUpdateRequest $request, $id)
    {
        try {
            $startTime = microtime(true);

            $validatedData = $request->validated();

            $this->userRepository->update($validatedData, $id);

            return response()->success($request, null, 'User Update Successfully', 201, $startTime, 1);
        } catch (Exception $e) {

            Log::channel('web_daily_error')->error('Error Updating User.' . $e->getMessage());

            return response()->error($request, null, $e->getMessage(), 500, $startTime);
        }
    }

    public function destory(Request $request, $id)
    {
        try {
            $startTime = microtime(true);

            $this->userRepository->destory($id);

            return response()->success($request, null, 'User Delete Successfully', 200, $startTime, 1);
        } catch (Exception $e) {

            Log::channel('web_daily_error')->error('Error Deleting User.' . $e->getMessage());

            return response()->error($request, null, $e->getMessage(), 500, $startTime);
        }
    }

    public function userInformationUpdate(Request $request, $id)
    {
        try {
            $startTime = microtime(true);

            $this->userRepository->userInformationUpdate($request, $id);

            return response()->success($request, null, 'User Update Successfully', 201, $startTime, 1);
        } catch (Exception $e) {
            Log::channel('web_daily_error')->error('Error Deleting User.' . $e->getMessage());

            return response()->error($request, null, $e->getMessage(), 500, $startTime);
        }
    }

    public function userChangePassword(UserChangePasswordRequest $request)
    {
        try {
            $startTime = microtime(true);

            $this->userRepository->userChangePassword($request);

            return response()->success($request, null, 'User Password change Successfully', 201, $startTime, 1);
        } catch (Exception $e) {
            Log::channel('web_daily_error')->error('Error Deleting User.' . $e->getMessage());

            return response()->error($request, null, $e->getMessage(), $e->getCode() ? $e->getCode() : 500, $startTime);
        }
    }
}
