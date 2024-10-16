<?php

namespace App\Http\Resources;

use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $averageRating = Rating::where('product_id', $this->id)->avg('rating') ?? 0;

        $averageRating = min(5, $averageRating);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'category_name' => optional($this->categories)->name,
            'description' => $this->description,
            "price" => $this->price,
            'average_rating' => round(min(5, $averageRating), 2),
            'max_rating' => 5,
            'images' => [],
            'attachments' => $this->attachments ? $this->attachments->map(function ($attachment) {
                return [
                    'id' => $attachment->id,
                    'name' => $attachment->name,
                    'attachment_url' => asset('storage/productImages/' . $attachment->name),
                ];
            }) : null,

        ];
    }
}
