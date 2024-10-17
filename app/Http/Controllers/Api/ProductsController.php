<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductsController extends Controller
{
    public function __construct(){
        $this->middleware('auth:sanctum')->except('index','show');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $product = Product::filter($request->query())
            ->with('category:id,name', 'store:id,name', 'tags:id,name')
            ->paginate();
        return ProductResource::collection($product);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'discription' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'status' => 'in:active,archived',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|gt:price',

        ]);
        $product =  Product::create($request->all());

        $user = $request->user();
        if(! $user->tokenCan('products.create')){
            abort(403,'Not allowed ');
        }

        return  Response::json($product,201,[
            'Location' =>route('products.show', $product->id)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return new ProductResource($product);

        return $product
            ->load('category:id,name', 'store:id,name', 'tags:id,name');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'discription' => 'nullable|string|max:255',
            'category_id' => 'sometimes|exists:categories,id',
            'status' => 'in:active,archived',
            'price' => 'sometimes|numeric|min:0',
            'compare_price' => 'nullable|numeric|gt:price',

        ]);
        $product->update($request->all());

        $user = $request->user();
        if(! $user->tokenCan('products.update')){
            abort(403,'Not allowed ');
        }

        return Response::json($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::guard('sanctum')->user();
        if(! $user->tokenCan('products.delete')){
            return response([
                'message' => 'Not allowed '
            ],403);
        }
        Product::destroy($id);
        return [
            'message' => 'Product deleted successfully'
        ];
    }
}
