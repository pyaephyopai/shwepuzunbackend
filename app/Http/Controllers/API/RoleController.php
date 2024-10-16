<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use App\Repositories\Role\RoleRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{

    protected RoleRepositoryInterface $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;

        $this->middleware('permission:getRoles', ['only' => ['getRoles']]);
    }

    public function getRoles(Request $request)
    {

        try {
            $startTime = microtime(true);

            $data = $this->roleRepository->getRoles();

            $result = RoleResource::collection($data);

            return response()->success($request, $result, 'Role Retrived Successfully', 200, $startTime);
        } catch (Exception $e) {
            Log::channel('web_daily_error')->error('Error Retriving Roles' . $e->getMessage());

            return response()->error($request, null, $e->getMessage(), 500, $startTime);
        }
    }
}
