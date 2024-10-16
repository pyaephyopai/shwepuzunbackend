<?php
namespace App\Repositories\OrderDetails;

use App\Models\OrderDetail;
use App\Repositories\OrderDetails\OrderDetailsRepositoryInterface;

class OrderDetailsRepository implements OrderDetailsRepositoryInterface
{
    public function store($request)
    {
        return OrderDetail::create($request);
    }
}
