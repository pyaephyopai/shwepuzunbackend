<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\RatingRequest;
use App\Repositories\Rating\RatingRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Log;

class RatingController extends Controller
{
    private RatingRepositoryInterface $ratingRepository;

    public function __construct(RatingRepositoryInterface $ratingRepository)
    {
        $this->ratingRepository = $ratingRepository;

        $this->middleware('permission:rating', ['only' => ['store']]);
    }

    public function store(RatingRequest $request)
    {
        try {
            $startTime = microtime(true);

            $this->ratingRepository->store($request);

            return response()->success($request, null, 'Rating create successfully', 201, $startTime);

        } catch (Exception $e) {
            Log::channel('web_daily_error')->error('Error creating' . $e->getMessage());

            return response()->error($request, null, $e->getMessage(), 500, $startTime);

        }
    }

}
