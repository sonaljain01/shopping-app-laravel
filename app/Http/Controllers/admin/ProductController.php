<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\TempImage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::latest('id')->with('product_images');

        if ($request->get('keyword') != "") {
            $products = $products->where('title', 'like', '%' . $request->get('keyword') . '%');
        }
        $products = $products->paginate(10);
        // dd($products);
        $data['products'] = $products;
        return view('admin.products.list', $data);
    }
    public function create()
    {
        $data = [];
        $categories = Category::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();
        $data['categories'] = $categories;
        $data['brands'] = $brands;
        return view('admin.products.create', $data);
    }

    public function store(Request $request)
    {
        // dd($request->image_array);
        // exit();
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products',
            'price' => 'required|numeric',
            'sku' => 'required',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required',
            'is_featured' => 'required|in:Yes,No',
            'description' => 'required',
        ];

        if (!empty($request->track_qty) && $request->track_qty == 'Yes') { {
                $rule['qty'] = 'required|numeric';
            }
            $validator = Validator::make($request->all(), $rules);
            if ($validator->passes()) {
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

                // save gallery 
                if (!empty($request->image_array)) {
                    foreach ($request->image_array as $temp_image_id) {

                        $tempImageInfo = TempImage::find($temp_image_id);
                        $extArray = explode('.', $tempImageInfo->name);
                        $ext = last($extArray);

                        $productImage = new ProductImage();
                        $productImage->product_id = $product->id;
                        $productImage->image = 'NULL';
                        $productImage->save();

                        $imageName = $product->id . '-' . $productImage->id . '-' . time() . '.' . $ext;
                        $productImage->image = $imageName;
                        $productImage->save();

                        //Thumbnail
                        $sourcePath = public_path() . '/temp-images' . $tempImageInfo->name;
                        $destPath = public_path() . '/uploads/product/large' . $imageName;
                        $image = $image->make($sourcePath);
                        $image->resize(1400, null, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                        $image->save($destPath);

                        //small
                        $destPath = public_path() . '/uploads/product/small' . $imageName;
                        $image = $image->make($sourcePath);
                        $image->fit(300, 300);
                        $image->save($destPath);
                    }
                }

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

    public function edit($id, Request $request)
    {

        $product = Product::find($id);
        $data = [];
        $data['product'] = $product;
        $categories = Category::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();
        $data['categories'] = $categories;
        $data['brands'] = $brands;
        return view('admin.products.edit', $data);
    }

    public function update($id, Request $request)
    {
        $product = Product::find($id);

        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products, slug, '.$product->id.',id',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products, sku, '.$product->id.',id',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required',
            'is_featured' => 'required|in:Yes,No',
            
        ];

        if (!empty($request->track_qty) && $request->track_qty == 'Yes') { {
                $rule['qty'] = 'required|numeric';
            }
            $validator = Validator::make($request->all(), $rules);
            if ($validator->passes()) {
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

                

                $request->session()->flash('success', 'Product Updated Successfully');
                return response()->json([
                    'status' => true,
                    'message' => 'Product Updated Successfully'
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