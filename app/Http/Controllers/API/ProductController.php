<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductShowResource;
use App\Repositories\Product\ProductRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    private ProductRepositoryInterface $productRepository;
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;

        $this->middleware('permission:productList', ['only' => ['index']]);
        $this->middleware('permission:productStore', ['only' => ['store']]);
        $this->middleware('permission:productShow', ['only' => ['show']]);
        $this->middleware('permission:productUpdate', ['only' => ['update']]);
        $this->middleware('permission:productDelete', ['only' => ['delete']]);

    }

    public function index(Request $request)
    {

        try {

            $startTime = microtime(true);

            $data = $this->productRepository->index($request);

            $result = ProductResource::collection($data);

            return response()->paginate($request, $result, 'Products Reterived Successfully', 200, $startTime);
        } catch (Exception $e) {

            Log::channel('web_daily_error')->error('Error Reteriving Products' . $e->getMessage());

            return response()->error($request, null, $e->getMessage(), 500, $startTime);
        }
    }

    public function store(ProductStoreRequest $request)
    {

        try {

            $startTime = microtime(true);

            $this->productRepository->store($request);

            return response()->success($request, null, 'Product Store Successfully', 201, $startTime);
        } catch (Exception $e) {

            Log::channel('web_daily_error')->error('Error Creating Product' . $e->getMessage());

            return response()->error($request, null, $e->getMessage(), 500, $startTime);
        }
    }

    public function show(Request $request, $id)
    {

        try {

            $startTime = microtime(true);

            $data = $this->productRepository->show($id);

            $result = new ProductShowResource($data);

            return response()->success($request, $result, 'Product Reterived Successfully', 200, $startTime);
        } catch (Exception $e) {

            Log::channel('web_daily_error')->error('Error Reteriving Product' . $e->getMessage());

            return response()->error($request, null, $e->getMessage(), 500, $startTime);
        }
    }

    public function update(ProductUpdateRequest $request, $id)
    {

        try {

            $startTime = microtime(true);

            $this->productRepository->update($request, $id);

            return response()->success($request, null, 'Product Update Successfully', 201, $startTime);
        } catch (Exception $e) {

            Log::channel('web_daily_error')->error('Error Updating Product' . $e->getMessage());

            return response()->error($request, null, $e->getMessage(), 500, $startTime);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {

            $startTime = microtime(true);

            $this->productRepository->destroy($id);

            return response()->success($request, null, 'Product Delete Successfully', 200, $startTime);
        } catch (Exception $e) {

            Log::channel('web_daily_error')->error('Error Deleting Product' . $e->getMessage());

            return response()->error($request, null, $e->getMessage(), 500, $startTime);
        }
    }

    public function productList(Request $request)
    {
        try {

            $startTime = microtime(true);

            $data = $this->productRepository->productList($request);

            $result = ProductResource::collection($data);

            return response()->success($request, $result, 'Product Retrieved Successfully', 200, $startTime);
        } catch (Exception $e) {

            Log::channel('web_daily_error')->error('Error Deleting Product' . $e->getMessage());

            return response()->error($request, null, $e->getMessage(), 500, $startTime);
        }
    }

    public function productListShow(Request $request, $id)
    {
        try {

            $startTime = microtime(true);

            $data = $this->productRepository->show($id);

            $result = new ProductShowResource($data);

            return response()->success($request, $result, 'Product Reterived Successfully', 200, $startTime);
        } catch (Exception $e) {

            Log::channel('web_daily_error')->error('Error Reteriving Product' . $e->getMessage());

            return response()->error($request, null, $e->getMessage(), 500, $startTime);
        }
    }

    public function productListRandom(Request $request, $id)
    {
        try {
            $startTime = microtime(true);

            $data = $this->productRepository->productListRandom($request, $id);

            $result = ProductResource::collection($data);

            return response()->success($request, $result, 'Product List Reterived Successfully', 200, $startTime);
        } catch (Exception $e) {
            Log::channel('web_daily_error')->error('Error Reteriving Product' . $e->getMessage());

            return response()->error($request, null, $e->getMessage(), 500, $startTime);
        }
    }
}
