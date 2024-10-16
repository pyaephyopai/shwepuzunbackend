<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContactResource;
use App\Repositories\Contact\ContactRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    private ContactRepositoryInterface $contactRepository;
    public function __construct(ContactRepositoryInterface $contactRepository)
    {

        $this->contactRepository = $contactRepository;
    }

    public function index(Request $request)
    {
        try {
            $startTime = microTime(true);

            $data = $this->contactRepository->index($request);

            $result = ContactResource::collection($data);

            return response()->success($request, $result, 'Contact Retrieved Successfully', 200, $startTime);
        } catch (Exception $e) {
            Log::channel('web_daily_error')->error('Error Retriving Category' . $e->getMessage());

            return response()->error($request, null, $e->getMessage(), $e->getCode() ? $e->getCode() : 500, $startTime);
        }
    }


    public function store(Request $request)
    {
        try {
            $startTime = microtime(true);

            $this->contactRepository->store($request);

            return response()->success($request, null, 'Contact Store Successfully', 201, $startTime);
        } catch (Exception $e) {
            Log::channel('web_daily_error')->error('Error Retriving Category' . $e->getMessage());

            return response()->error($request, null, $e->getMessage(), $e->getCode() ? $this->getCode() : 500, $startTime);
        }
    }
}
