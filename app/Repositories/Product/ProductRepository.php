<?php

namespace App\Repositories\Product;

use App\Models\Product;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Service\AttachmentService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductRepository implements ProductRepositoryInterface
{

    private AttachmentService $attachmentService;

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
        $category = $request->input('category');
        $minPrice = $request->input('minPrice');
        $maxPrice = $request->input('maxPrice');
        $priceSort = $request->input('priceSort');
        $dateSort = $request->input('dateSort');

        return Product::productFilter($search, $category, $minPrice, $maxPrice, $priceSort, $dateSort)->with('ratings', 'categories', 'attachments')->orderBy('created_at', 'desc')->paginate($this->limit($request));
    }

    public function store($request)
    {
        $product = Product::create([

            'name' => $request['name'],
            'category_id' => $request['category_id'],
            'price' => $request['price'],
            'description' => $request['description'],
        ]);

        if ($request->hasFile('images')) {
            $images = $request->file('images');

            foreach ($images as $image) {

                $fileName = $image->getClientOriginalName();

                $image->storeAs('public/productImages', $fileName);

                $data = [
                    'product_id' => $product->id,
                    'name' => $fileName,
                ];

                $this->attachmentService->createAttachment($data);
            }
        }
        return $product;
    }

    public function show($id)
    {

        $product = Product::where('id', $id)->first();

        if (!$product) {
            throw new Exception('Product not Found!.', 404);
        }

        return $product;
    }

    public function update($validatedData, $id)
    {

        $product = Product::with('attachments')->where('id', $id)->first();

        if (!$product) {
            throw new Exception('Product not Found!.', 404);
        }

        $oldImages = $product->attachments;

        if (isset($validatedData->images)) {

            foreach ($oldImages as $oldImage) {

                Storage::disk('public')->delete('productImages/' . $oldImage->name);

                $oldImage->delete();
            }

            foreach ($validatedData->images as $image) {
                $fileName = uniqid() . '-' . $image->getClientOriginalName();

                $image->storeAs('public/productImages', $fileName);

                $data = [
                    'name' => $fileName,
                    'product_id' => $product->id,
                ];

                $this->attachmentService->createAttachment($data);
            }
        }

        return $product->update([
            'name' => $validatedData['name'],
            'category_id' => $validatedData['category_id'],
            'price' => $validatedData['price'],
            'description' => $validatedData['description'],
        ]);
    }

    public function destroy($id)
    {

        $product = Product::where('id', $id)->first();

        if (!$product) {
            throw new Exception('Product not Found!.', 404);
        }

        if ($product->attachments()->exists()) {

            foreach ($product->attachments as $oldImage) {

                Storage::disk('public')->delete('productImages/' . $oldImage->name);

                $oldImage->delete();
            }
        }

        return $product->delete();
    }

    public function productList(Request $request)
    {

        $search = $request->input('search');
        $category = $request->input('category');
        $minPrice = $request->input('minPrice');
        $maxPrice = $request->input('maxPrice');
        $priceSort = $request->input('priceSort');
        $dateSort = $request->input('dateSort');

        $products = Product::productFilter($search, $category, $minPrice, $maxPrice, $priceSort, $dateSort)->select('products.*') // select all the product
            ->leftJoin(DB::raw('(SELECT product_id, AVG(rating) as average_rating FROM ratings GROUP BY product_id) as avg_ratings'), 'products.id', '=', 'avg_ratings.product_id')
            ->orderBy(DB::raw('COALESCE(avg_ratings.average_rating, 0)'), 'desc') // Use COALESCE to handle null values
            ->orderBy('created_at', 'desc')
            ->with('ratings', 'categories', 'attachments')
            ->paginate($this->limit($request));

        return $products;
    }

    public function productListRandom(Request $request, $id)
    {
        return Product::where('id', '!=', $id)->inRandomOrder()->limit(4)->get();
    }
}
