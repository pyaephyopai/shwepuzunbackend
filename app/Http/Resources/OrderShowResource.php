<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $paymetnType = $this->payment_type;

        switch ($paymetnType) {

            case $paymetnType === 1:
                $paymetnType = 'Cash On Delivery';
                break;

            case $paymetnType === 2:
                $paymetnType = 'POS on Delivery';
                break;

            case $paymetnType === 3:
                $paymetnType = 'Online Payment';
                break;

            default:

                break;
        }

        return [
            'id' => $this->id,
            'user_name' => optional($this->user)->name,
            'order_code' => $this->order_code,
            'total_price' => $this->total_price,
            'payment_type' => $paymetnType,
            'notes' => $this->notes ? $this->notes : "",
            'status' => $this->status,
            'order_details' => $this->orderDetails ? $this->orderDetails->map(function ($detail) {
                return [
                    'user_name' => optional($detail->users)->name,
                    'product_name' => optional($detail->products)->name,
                    'product_id' => $detail->product_id,
                    'product_image' => $detail->products->attachments[0]['name'],
                    'product_image_url' => asset('storage/productImages/' . $detail->products->attachments[0]['name']),
                    'qty' => $detail->qty,
                    'price' => $detail->price,
                    'total' => $detail->qty * $detail->price,
                ];
            }) : [],
        ];
    }
}
