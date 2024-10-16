<?php

namespace App\Http\Resources;

use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ProductResource extends JsonResource
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

        $isHotProduct = $averageRating >= 4;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'category_name' => optional($this->categories)->name,
            'description' => Str::limit($this->description, 25, '...'),
            'average_rating' => round(min(5, $averageRating), 2),
            'max_rating' => 5,
            'image' => $this->attachments[0]['name'],
            'image_url' => asset('storage/productImages/' . $this->attachments[0]['name']),
            'is_hot_product' => $isHotProduct,
        ];
    }
}
