<?php

namespace App\Repositories\Order;

use App\Models\Order;
use App\Repositories\OrderDetails\OrderDetailsRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class OrderRepository implements OrderRepositoryInterface
{
    private OrderDetailsRepositoryInterface $orderDetailsRepository;

    public function __construct(OrderDetailsRepositoryInterface $orderDetailsRepository)
    {
        $this->orderDetailsRepository = $orderDetailsRepository;
    }

    protected function limit(Request $request)
    {
        $limit = (int) $request->input('limit', Config::get('paginate.default_limit'));

        return $limit;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $name = $request->input('name', 'id');
        $orderBy = $request->input('orderBy', 'desc');
        $status = $request->input('status');
        $payment = $request->input('payment');

        return Order::orderFilter($search, $status, $payment)
            ->with('orderDetails')
            ->orderBy($name, $orderBy)
            ->paginate($this->limit($request));
    }

    public function store($request)
    {
        DB::transaction(function () use ($request) {

            $lastOrder = Order::latest()->first();
            $orderNumber = $lastOrder ? (int) substr($lastOrder->order_code, 4) + 1 : 1;
            $orderCode = 'ORD-' . str_pad($orderNumber, 4, '0', STR_PAD_LEFT);
            $userId = Auth::user()->id;

            $order = Order::create([
                'user_id' => $userId,
                'order_code' => $orderCode,
                'total_price' => $request->total_price,
                'payment_type' => $request->payment_type,
                'notes' => $request->notes,
            ]);

            if ($request->products) {
                foreach ($request->products as $product) {
                    $orderDetails = [
                        'user_id' => $userId,
                        'product_id' => $product['id'],
                        'order_id' => $order['id'],
                        'qty' => (int) $product['qty'],
                        'price' => (int) $product['price'],
                        'total' => (int) ($product['qty'] * $product['price']),
                    ];

                    $this->orderDetailsRepository->store($orderDetails);
                }
            }
        });
    }

    public function show($id)
    {
        $order = Order::with('orderDetails',)->where('id', $id)->first();

        if (!$order) {
            throw new Exception('Order not found.', 404);
        }

        return $order;
    }

    public function update($request, $id)
    {
        return Order::where('id', $id)->update([
            'total_price' => $request->total_price,
            'payment_type' => $request->payment_type,
            'notes' => $request->notes,
            'status' => $request->status,
        ]);
    }

    public function changeStatus($request, $id)
    {
        $order = Order::where('id', $id)->first();

        if (!$order) {
            throw new Exception('Order not found!', 404);
        }
        return $order->update([
            'status' => $request->status,
        ]);
    }

    public function userOrders($request)
    {
        return Order::where('user_id', Auth::user()->id)->with('user', 'orderDetails')->paginate($this->limit($request));
    }
}
