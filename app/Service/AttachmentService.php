<?php
namespace App\Service;

use App\Models\Attachment;
use Exception;
use Illuminate\Support\Facades\Storage;

class AttachmentService
{
    public function createAttachment($data)
    {

        Attachment::create($data);

    }

    public function attachmentDelete($id)
    {
        $attachment = Attachment::where('id', $id)->first();

        if (!$attachment) {
            throw new Exception('Attachment not Found!.', 404);
        }

        Storage::disk('public')->delete('blogImages/' . $attachment->name);

        $attachment->delete();

    }

}
