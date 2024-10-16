<?php

namespace App\Repositories\Rating;

use App\Models\Rating;
use App\Repositories\Rating\RatingRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingRepository implements RatingRepositoryInterface
{
    public function store(Request $request)
    {

        return Rating::updateOrCreate([
            'user_id' => Auth::user()->id,
            'product_id' => $request->product_id,

        ], [
            'rating' => $request->rating,
        ]);
    }
}
