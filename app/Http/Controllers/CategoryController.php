<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\ResResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
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
            'category_name' => 'required|unique:categories'
        ]);

        if($validate->fails()){
            return response()->json([
                "status" => false,
                "message" => $validate->errors()
            ], 400);
        }else {
            $insertData = Category::create([
                'category_name' => $request->category_name,
                'user_id' => $request->user()->id
            ]);

            return response()->json([
                "status" => true,
                "message" => "Product category successfully added!",
                "data" => $insertData
            ], 200);
        }
    }

    public function getCategory(Request $request){
        $data = DB::table('categories')->where('user_id', $request->user()->id)->get();

        return response()->json([
            "status" => true,
            "message" => "Success",
            "data" => $data
        ], 200);

    }
    
    public function update(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "category_name" => "required",
            "id" => "required"
        ]);

        if($validate->fails()){
            return response()->json([
                "status" => false,
                "message" => $validate->errors()
            ], 400);
        }else {
            $update = Category::where('id', $request->id)
            ->where('user_id', $request->user()->id)
            ->update(['category_name' => $request->category_name]);

            if($update) return new ResResource(true, "Category successfuly updated!", $update);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $delete = Category::find($request->id)->delete();

        return new ResResource(true, 'Category has been deleted!', $delete);
    }
}
