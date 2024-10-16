<?php
namespace App\Repositories\Rating;

use Illuminate\Http\Request;

interface RatingRepositoryInterface
{
    public function store(Request $request);
}
