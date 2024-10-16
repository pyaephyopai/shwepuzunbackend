<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlogRequest;
use App\Http\Requests\BlogUpdateRequest;
use App\Http\Resources\BlogResource;
use App\Repositories\Blog\BlogRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BlogController extends Controller
{

    private BlogRepositoryInterface $blogRepository;

    public function __construct(BlogRepositoryInterface $blogRepository)
    {
        $this->blogRepository = $blogRepository;

        $this->middleware('permission:getBlogs', ['only' => ['index']]);

        $this->middleware('permission:blogStore', ['only' => ['store']]);

        $this->middleware('permission:blogShow', ['only' => ['show']]);

        $this->middleware('permission:blogUpdate', ['only' => ['update']]);

        $this->middleware('permission:blogDelete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        try {

            $startTime = microtime(true);

            $data = $this->blogRepository->index($request);

            $result = BlogResource::collection($data);

            return response()->paginate($request, $result, 'Blog Retrieved Successfully', 200, $startTime, 1);
        } catch (Exception $e) {

            Log::channel('web_daily_error')->error('Error Retriving Blog' . $e->getMessage());

            return response()->error($request, null, $e->getMessage(), 500, $startTime);
        }
    }

    public function store(BlogRequest $request)
    {

        try {

            $startTime = microtime(true);

            $this->blogRepository->store($request);

            return response()->success($request, null, 'Blog Create Successfully', 201, $startTime, 1);
        } catch (Exception $e) {

            Log::channel('web_daily_error')->error('Error Creating Blog' . $e->getMessage());

            return response()->error($request, null, $e->getMessage(), 500, $startTime);
        }
    }

    public function show(Request $request, $id)
    {
        try {

            $startTime = microtime(true);

            $data = $this->blogRepository->show($id);

            $result = new BlogResource($data);

            return response()->success($request, $result, 'Blog Retrieved Successfully', 200, $startTime, 1);
        } catch (Exception $e) {

            Log::channel('web_daily_error')->error('Error Retriving Blog' . $e->getMessage());

            return response()->error($request, null, $e->getMessage(), 500, $startTime);
        }
    }

    public function update(BlogUpdateRequest $request, int $id)
    {
        try {

            $startTime = microtime(true);

            $this->blogRepository->update($request, $id);

            return response()->success($request, null, 'Blog updated Successfully', 201, $startTime, 1);
        } catch (Exception $e) {

            Log::channel('web_daily_error')->error('Error Updating Blog' . $e->getMessage());

            return response()->error($request, null, $e->getMessage(), 500, $startTime);
        }
    }

    public function destroy(Request $request, int $id)
    {
        try {

            $startTime = microtime(true);

            $this->blogRepository->destroy($id);

            return response()->success($request, null, 'Blog Deleted Successfully', 200, $startTime, 1);
        } catch (Exception $e) {

            Log::channel('web_daily_error')->error('Error Deleting Blog' . $e->getMessage());

            return response()->error($request, null, $e->getMessage(), 500, $startTime);
        }
    }

    public function blogList(Request $request)
    {
        try {

            $startTime = microtime(true);

            $data = $this->blogRepository->index($request);

            $result = BlogResource::collection($data);

            return response()->paginate($request, $result, 'Blog Retrieved Successfully', 200, $startTime, 1);
        } catch (Exception $e) {

            Log::channel('web_daily_error')->error('Error Retriving Blog' . $e->getMessage());

            return response()->error($request, null, $e->getMessage(), 500, $startTime);
        }
    }

    public function blogListShow(Request $request, $id)
    {
        try {

            $startTime = microtime(true);

            $data = $this->blogRepository->show($id);

            $result = new BlogResource($data);

            return response()->success($request, $result, 'Blog Retrieved Successfully', 200, $startTime, 1);
        } catch (Exception $e) {

            Log::channel('web_daily_error')->error('Error Retriving Blog' . $e->getMessage());

            return response()->error($request, null, $e->getMessage(), 500, $startTime);
        }
    }

    public function blogListRandom(Request $request, $id)
    {
        try {

            $startTime = microtime(true);

            $data = $this->blogRepository->blogListRandom($id);

            $result = BlogResource::collection($data);

            return response()->success($request, $result, 'Blog Retrieved Successfully', 200, $startTime, 1);
        } catch (Exception $e) {

            Log::channel('web_daily_error')->error('Error Retriving Blog' . $e->getMessage());

            return response()->error($request, null, $e->getMessage(), 500, $startTime);
        }
    }
}
