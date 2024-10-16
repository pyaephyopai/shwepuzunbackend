<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{

    public function toArray(Request $request): array
    {


        return [
            'id' => $this->id,
            'user_name' => optional($this->user)->name,
            'order_code' => $this->order_code,
            'total_price' => $this->total_price,
            'payment_type' => $this->payment_type,
            'notes' => $this->notes ? $this->notes : "",
            'order_detail_total' => count($this->orderDetails),
            'status' => $this->status,
        ];
    }
}
