<?php

namespace App\Repositories\Blog;

use App\Models\Blog;
use App\Service\AttachmentService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class BlogRepository implements BlogRepositoryInterface
{

    protected AttachmentService $attachmentService;
    public function __construct(AttachmentService $attachmentService)
    {
        $this->attachmentService = $attachmentService;
    }

    protected function limit(Request $request)
    {
        $limit = (int) $request->input('limit', Config::get('paginate.default_limit'));

        return $limit;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        return Blog::blogFilter($search)->with('attachments')
            ->orderBy('id', 'desc')
            ->paginate($this->limit($request));
    }

    public function store($request)
    {

        $blog = Blog::create([
            'title' => $request['title'],
            'description' => $request['description'],
        ]);

        if (isset($request->images)) {

            foreach ($request->images as $image) {

                $fileName = uniqid() . '-' . $image->getClientOriginalName();

                $image->storeAs('public/blogImages', $fileName);

                $data = [
                    'name' => $fileName,
                    'blog_id' => $blog->id,
                ];

                $this->attachmentService->createAttachment($data);
            }
        }

        return $blog;
    }

    public function show($id)
    {
        $blog = Blog::with('attachments')->where('id', $id)->first();

        if (!$blog) {
            throw new Exception('Blog not Found !', 404);
        }

        return $blog;
    }

    public function update($validatedData, $id)
    {
        $blog = Blog::with('attachments')->where('id', $id)->first();

        if (!$blog) {
            throw new Exception('Blog not Found!', 404);
        }

        if ($validatedData->hasFile('images')) {

            foreach ($validatedData->images as $image) {

                $fileName = uniqid() . '-' . $image->getClientOriginalName();

                $image->storeAs('public/blogImages', $fileName);

                $data = [
                    'name' => $fileName,
                    'blog_id' => $blog->id,
                ];

                $this->attachmentService->createAttachment($data);
            }
        }

        $blog->update([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
        ]);

        return $blog;
    }

    public function destroy($id)
    {
        $blog = Blog::with('attachments')->where('id', $id)->first();

        if (!$blog) {
            throw new Exception("Blog not Found !", 404);
        }

        $attachments = $blog->attachments;

        if ($attachments) {

            foreach ($attachments as $images) {

                Storage::disk('public')->delete('blogImages/' . $images->name);

                $images->delete();
            }
        }

        $blog->delete();
    }

    public function blogListRandom($id)
    {
        return Blog::where('id', '!=', $id)->inRandomOrder()->limit(4)->get();
    }
}
