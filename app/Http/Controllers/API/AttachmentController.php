<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Service\AttachmentService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AttachmentController extends Controller
{
    private AttachmentService $attachmentService;
    public function __construct(AttachmentService $attachmentService)
    {
        $this->attachmentService = $attachmentService;
    }

    public function attachmentDelete(Request $request, $id)
    {
        try {
            $startTime = microtime(true);

            $this->attachmentService->attachmentDelete($id);

            return response()->success($request, null, 'Attachment Delete Successfully', 200, $startTime, 1);

        } catch (Exception $e) {

            Log::channel('web_daily_error')->error('Error Creating User.' . $e->getMessage());

            return response()->error($request, null, $e->getMessage(), 500, $startTime);

        }
    }

}
