<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ResResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "name" => 'required',
            "total" => 'required',
            "category_id" => 'required'
        ]);

        if($validate->fails()){
            return response()->json([
                "status" => false,
                "message" => $validate->errors()
            ],400);
        }else {
            $insertData = Product::create([
                "name" => $request->name,
                "total" => $request->total,
                "price" => $request->price,
                "user_id" => $request->user()->id,
                "category_id" => $request->category_id
            ]);

            return new ResResource(true, 'Success', $insertData);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $limit = $request->query('limit') ? $request->query('limit') : 5;
        $offset = $request->query('offset') ? $request->query('offset') : 0;

        $order = $request->query('order') ? $request->query('order') : 'DESC';
        $short = $request->query('short') ? $request->query('short') : 'id';

        $data = Product::with('categories')
        ->where('products.user_id', $request->user()->id)
        ->where('products.name', 'LIKE', '%'.$request->query('search').'%')
        ->limit($limit)
        ->offset($offset)
        ->orderBy('products.'.$short, $order)
        ->get();

        return new ResResource(true, 'Success', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "id" => 'required'
        ]);

        if($validate->fails()){
            return response()->json([
                "status" => false,
                "message" => $validate->errors()
            ],400);
        }else {
            $product = Product::find([
                ['id', '=', $request->id],
                ['user_id', '=', $request->user()->id]
            ])->first();
            $product->update([
                "name" => $request->name ? $request->name : $product->name,
                "total" => $request->total ? $request->total : $product->total,
                "category_id" => $request->category_id ? $request->category_id : $product->category_id,
            ]);

            return new ResResource(true, 'Data product successfuly updated!', $product);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "id" => 'required'
        ]);

        if($validate->fails()){
            return response()->json([
                "status" => false,
                "message" => $validate->errors()
            ],400);
        }

        $delete = Product::where('id', $request->id)
        ->where('products.user_id', $request->user()->id)
        ->delete();

        if($delete) return new ResResource(true, 'Product has been deleted!', $delete);
    }
}
