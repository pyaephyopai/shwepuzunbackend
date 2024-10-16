<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\OrderDetails\OrderDetailsRepositoryInterface;
use Exception;
use Illuminate\Http\Request;

class OrderDetailController extends Controller
{

    private OrderDetailsRepositoryInterface $orderDetailsRepository;

    public function __construct(OrderRepositoryInterface $orderDetailsRepository)
    {
        $this->orderDetailsRepository  = $orderDetailsRepository;
    }

    public function store(Request $request)
    {
        try {
            $startTime = microtime(true);

            $this->orderDetailsRepository->store($request);
        } catch (Exception $e) {
        }
    }
}
