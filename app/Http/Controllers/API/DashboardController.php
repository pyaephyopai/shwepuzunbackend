<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Dashboard\DashboardRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{

    private DashboardRepositoryInterface $dashboardRepository;

    public function __construct(DashboardRepositoryInterface $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }

    public function dashboard(Request $request)
    {
        try {
            $startTime = microtime(true);

            $data = $this->dashboardRepository->dashboard($request);

            return response()->success($request, $data, 'Dashboard data retrieved successfully', 200, $startTime);
        } catch (Exception $e) {
            Log::channel('web_daily_error')->error('Error Retriving dashboard data' . $e->getMessage());

            return response()->error($request, null, $e->getMessage(), 500, $startTime);
        }
    }
    public function monthlySales(Request $request)
    {
        try {
            $startTime = microtime(true);

            $data = $this->dashboardRepository->monthlySales($request);

            return response()->success($request, $data, 'Monthly sales retrieved successfully', 200, $startTime);
        } catch (Exception $e) {

            Log::channel('web_daily_error')->error('Error Retriving monthly sales' . $e->getMessage());

            return response()->error($request, null, $e->getMessage(), 500, $startTime);
        }
    }

    public function monthlyUsers(Request $request)
    {
        try {
            $startTime = microtime(true);

            $data = $this->dashboardRepository->monthlyUsers($request);

            return response()->success($request, $data, 'Monthly users retrieved successfully', 200, $startTime);
        } catch (Exception $e) {

            Log::channel('web_daily_error')->error('Error Retriving monthly users' . $e->getMessage());

            return response()->error($request, null, $e->getMessage(), 500, $startTime);
        }
    }
}
