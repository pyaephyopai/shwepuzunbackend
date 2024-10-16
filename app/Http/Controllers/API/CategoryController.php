<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Repositories\Category\CategoryRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    protected CategoryRepositoryInterface $categoryRepository;
    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;

        $this->middleware('permission:getCategory', ['only' => ['index']]);
        $this->middleware('permission:categoryStore', ['only' => ['store']]);
        $this->middleware('permission:categoryShow', ['only' => ['show']]);
        $this->middleware('permission:categoryUpdate', ['only' => ['update']]);
        $this->middleware('permission:categoryDelete', ['only' => ['destroy']]);

    }

    public function index(Request $request)
    {
        try {
            $startTime = microtime(true);

            $data = $this->categoryRepository->index($request);

            $result = CategoryResource::collection($data);

            return response()->paginate($request, $result, 'Category Retrieved Successfully', 200, $startTime);
        } catch (Exception $e) {

            Log::channel('web_daily_error')->error('Error Retriving Category' . $e->getMessage());

            return response()->error($request, null, $e->getMessage(), 500, $startTime);
        }
    }

    public function store(CategoryRequest $request)
    {
        try {
            $startTime = microtime(true);

            $validatedData = $request->validated();

            $this->categoryRepository->store($validatedData);

            return response()->success($request, null, 'Category Create Successfully', 201, $startTime);
        } catch (Exception $e) {

            Log::channel('web_daily_error')->error('Error Creating Category' . $e->getMessage());

            return response()->error($request, null, $e->getMessage(), 500, $startTime);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $startTime = microtime(true);

            $data = $this->categoryRepository->show($id);

            $result = new CategoryResource($data);

            return response()->success($request, $result, 'Category Retrieved Successfully', 200, $startTime);
        } catch (Exception $e) {

            Log::channel('web_daily_error')->error('Error Retriving Category' . $e->getMessage());

            return response()->error($request, null, $e->getMessage(), 500, $startTime);
        }
    }

    public function update(CategoryRequest $request, $id)
    {
        try {

            $startTime = microtime(true);

            $validatedData = $request->validated();

            $this->categoryRepository->update($validatedData, $id);

            return response()->success($request, null, 'Category Update Successfully', 201, $startTime);
        } catch (Exception $e) {

            Log::channel('web_daily_error')->error('Error Updating Category' . $e->getMessage());

            return response()->error($request, null, $e->getMessage(), 500, $startTime);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $startTime = microtime(true);

            $this->categoryRepository->destroy($id);

            return response()->success($request, null, 'Category Delete Successfully', 200, $startTime);
        } catch (Exception $e) {

            Log::channel('web_daily_error')->error('Error Delete Category' . $e->getMessage());

            return response()->error($request, null, $e->getMessage(), 500, $startTime);
        }
    }

    public function categoryList(Request $request)
    {

        try {
            $startTime = microtime(true);

            $data = $this->categoryRepository->categoryList($request);

            $result = CategoryResource::collection($data);

            return response()->success($request, $result, 'Category Retrieved Successfully', 200, $startTime);
        } catch (Exception $e) {

            Log::channel('web_daily_error')->error('Error Retriving Category' . $e->getMessage());

            return response()->error($request, null, $e->getMessage(), 500, $startTime);
        }
    }
}
