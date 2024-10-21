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
use Intervention\Image\ImageManagerStatic as Image;
use Storage;
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
            'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];

        if (!empty($request->track_qty) && $request->track_qty == 'Yes') { {
                $rules['qty'] = 'required|numeric';
            }
            $validator = Validator::make($request->all(), $rules);
            

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors(),
                ]);
            }
        
            // Store product details
            $product = Product::create([
                'title' => $request->title,
                'slug' => $request->slug,
                'price' => $request->price,
                'sku' => $request->sku,
                'track_qty' => $request->track_qty,
                'qty' => $request->qty,
                'category_id' => $request->category,
                'brand_id' => $request->brand,
                'is_featured' => $request->is_featured,
                'description' => $request->description,
                'status' => $request->status,
                'barcode' => $request->barcode,
                'compare_price' => $request->compare_price,
            ]);

                if ($request->hasFile('image')) {
                    foreach ($request->file('image') as $image) {
                        $ext = $image->getClientOriginalExtension();
                        $newName = time() . '-' . uniqid() . '.' . $ext;

                        // $productImage = new ProductImage();
                        // $productImage->product_id = $product->id;  
                        // $productImage->image = 'uploads/product/' . $newName;
                        // $productImage->name = $newName;
                        // $productImage->save();

                        $product = ProductImage::create([
                            "product_id" => $product->id,
                            "image"=> 'uploads/product/' . $newName,
                            "name" => $newName
                            ]);
                            
                            if(!$product){
                            return back()->with("error", "there is error");}
            
                        // Move the image to the uploads folder
                        $image->move(public_path('uploads/product'), $newName);
                    }
                }

                $request->session()->flash('success', 'Product Created Successfully');
                return response()->json([
                    'status' => true,
                    'message' => 'Product Created Successfully'
                ]);
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
            'slug' => 'required|unique:products, slug, ' . $product->id . ',id',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products, sku, ' . $product->id . ',id',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required',
            'is_featured' => 'required|in:Yes,No',

        ];

        if (!empty($request->track_qty) && $request->track_qty == 'Yes') { {
                $rules['qty'] = 'required|numeric';
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

    public function destroy($id, Request $request)
    {
        $product = Product::find($id);
        if (empty($product)) {
            return redirect()->route('products.index');
        }

        // File::delete(public_path() . '/uploads/category/' . $category->image);

        $product->delete();

        $request->session()->flash('success', 'Product deleted successfully');

        return response()->json([
            'status' => true,
            'message' => 'Product deleted successfully'
        ]);
    }
}