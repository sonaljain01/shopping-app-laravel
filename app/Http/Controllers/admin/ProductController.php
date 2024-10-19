<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
class ProductController extends Controller
{
    public function create()
    {
        $data=[];
        $categories = Category::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();
        $data['categories'] = $categories;
        $data['brands'] = $brands;
        return view('admin.products.create', $data);
    }

    public function store(Request $request)
    {
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products',
            'price' => 'required|numeric',
            'sku'   => 'required',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required',
            'is_featured' => 'required|in:Yes,No',
            'description' => 'required',
        ];

        if(!empty($request->track_qty) && $request->track_qty == 'Yes'){ {
            $rule['qty'] = 'required|numeric';
        }
        $validator = Validator::make($request->all(), $rules);
        if($validator->passes()){
            $product = new Product();
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->price = $request->price;
            $product->sku = $request->sku;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->category_id = $request->category;
            $product->brand_id = $request->brand;
            $product->is_featured = $request->is_featured;
            $product->description = $request->description;
            $product->status = $request->status;
            $product->barcode = $request->barcode;
            $product->compare_price = $request->compare_price;
            $product->save();
            $request->session()->flash('success', 'Product Created Successfully');
            return response()->json([
                'status' => true,
                'message' => 'Product Created Successfully'
            ]);
        } else {

            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
}
}