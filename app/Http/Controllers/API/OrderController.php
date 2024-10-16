<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderStoreRequest;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderShowResource;
use App\Repositories\Order\OrderRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    private OrderRepositoryInterface $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->middleware('permission:orderList', ['only' => ['index']]);
        $this->middleware('permission:orderStore', ['only' => ['store']]);
        $this->middleware('permission:orderShow', ['only' => ['show']]);
        $this->middleware('permission:orderUpdate', ['only' => ['update']]);
        $this->middleware('permission:orderDelete', ['only' => ['delete']]);
        $this->middleware('permission:orderChangeStatus', ['only' => ['changeStatus']]);
    }

    public function index(Request $request)
    {
        try {

            $startTime = microtime(true);

            $data = $this->orderRepository->index($request);

            $result = OrderResource::collection($data);

            return response()->paginate($request, $result, 'Order Retrieved Successfully', 200, $startTime);
        } catch (Exception $e) {

            Log::channel('web_daily_error')->error('Error Retriving Order' . $e->getMessage());

            return response()->error(request(), null, $e->getMessage(), $e->getCode() ? $e->getCode() : 500, $startTime);
        }
    }

    public function store(OrderStoreRequest $request)
    {
        try {
            $startTime = microtime(true);

            $this->orderRepository->store($request);

            return response()->success($request, null, 'Order Created Successfully', 201, $startTime);
        } catch (Exception $e) {
            Log::channel('web_daily_error')->error('Error Creating Order' . $e->getMessage());

            return response()->error(request(), null, $e->getMessage(), $e->getCode() ? $e->getCode() : 500, $startTime);
        }
    }

    public function show($id)
    {
        try {

            $startTime = microtime(true);

            $data = $this->orderRepository->show($id);

            $result = new OrderShowResource($data);

            return response()->success(request(), $result, 'Order Retrieved Successfully', 200, $startTime);
        } catch (Exception $e) {

            Log::channel('web_daily_error')->error('Error Retriving Order' . $e->getMessage());

            return response()->error(request(), null, $e->getMessage(), $e->getCode() ? $e->getCode() : 500, $startTime);
        }
    }

    public function update(Request $request, string $id)
    {

        try {
            $startTime = microtime(true);

            $this->orderRepository->update($request, $id);

            return response()->success($request, null, 'Order Updated Successfully', 201, $startTime);
        } catch (Exception $e) {
            Log::channel('web_daily_error')->error('Error Creating Order' . $e->getMessage());

            return response()->error(request(), null, $e->getMessage(), $e->getCode() ? $e->getCode() : 500, $startTime);
        }
    }

    public function changeStatus(Request $request, $id)
    {

        try {
            $startTime = microtime(true);

            $this->orderRepository->changeStatus($request, $id);

            return response()->success($request, null, 'Order status changed', 201, $startTime);
        } catch (Exception $e) {
            Log::channel('web_daily_error')->error('Error Changing Status Order' . $e->getMessage());

            return response()->error(request(), null, $e->getMessage(), $e->getCode() ? $e->getCode() : 500, $startTime);
        }
    }
    public function userOrders(Request $request)
    {
        try {
            $startTime = microtime(true);

            $data = $this->orderRepository->userOrders($request);

            $result = OrderShowResource::collection($data);

            return response()->success($request, $result, 'User orders retrieved Successfully', 200, $startTime);
        } catch (Exception $e) {
            Log::channel('web_daily_error')->error('Error retireving user orders' . $e->getMessage());

            return response()->error(request(), null, $e->getMessage(), $e->getCode() ? $e->getCode() : 500, $startTime);
        }
    }
}
