<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\ProductImage;
use Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::latest('id')->with('product_images');

        if ($request->get('keyword') != "") {
            $products = $products->where('title', 'like', '%' . $request->get('keyword') . '%');
        }
        $products = $products->paginate(10);
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

        $rules = [
            'title' => 'required|unique:products',
            'slug' => 'nullable|unique:products',
            'price' => 'required|numeric',
            'sku' => 'required',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required',
            'is_featured' => 'required|in:Yes,No',
            'description' => 'required',
            'image.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];

        if (!empty($request->track_qty) && $request->track_qty == 'Yes') { {
                $rules['qty'] = 'required|numeric';
            }
            $validator = Validator::make($request->all(), $rules);


            if ($validator->fails()) {
                $request->session()->flash('error', $validator->errors()->first());
                return redirect()->route('products.index');
            }
            $slug = null;
            if ($request->slug) {
                $isProductExist = Product::where('slug', $request->slug)->first();
                if ($isProductExist) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Blog with this slug already exist',
                    ]);
                } else {
                    $slug = $request->slug;
                }
            } else {
                $slug = $this->slug($request->title);
            }

            // Store product details
            $product = Product::create([
                'title' => $request->title,
                'slug' => $slug,
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

                    $image->move(public_path('uploads/product'), $newName);


                    $product = ProductImage::create([
                        "product_id" => $product->id,
                        "image" => 'uploads/product/' . $newName,
                        "name" => $newName
                    ]);
                    if (!$product) {
                        return back()->with("error", "there is error");
                    }

                }
            }

            $request->session()->flash('success', 'Product Created Successfully');
            return redirect()->route('products.index');
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

    public function update(Request $request, $id)
    {

        $product = Product::where('id', $id)->with('product_images')->first();
        $rules = [
            'title' => 'required|unique:products, title, ' . $product->id . ',id',
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

                if ($request->hasFile('image')) {
                    // Delete old images from the database and file system
                    foreach ($product->product_images as $image) {
                        $imagePath = public_path($image->image);
                        if (file_exists($imagePath)) {
                            unlink($imagePath); // Delete the image file
                        }
                        $image->delete(); // Delete the image record from the database
                    }

                    // Upload new images
                    foreach ($request->file('image') as $image) {
                        $ext = $image->getClientOriginalExtension();
                        $newName = time() . '-' . uniqid() . '.' . $ext;

                        $image->move(public_path('uploads/product'), $newName);

                        ProductImage::create([
                            "product_id" => $product->id,
                            "image" => 'uploads/product/' . $newName,
                            "name" => $newName
                        ]);
                    }
                }
                $request->session()->flash('success', 'Product Updated Successfully');
                return redirect()->route('products.index');
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
        $product = Product::with('product_images')->find($id);
        if (empty($product)) {
            return redirect()->route('products.index');
        }

        foreach ($product->product_images as $image) {
            $imagePath = public_path($image->image);
            if (file_exists($imagePath)) {
                unlink($imagePath); // Delete the image file
            }
            $image->delete(); // Delete the image record from the database
        }
        $product->delete();

        $request->session()->flash('success', 'Product deleted successfully');

        return response()->json([
            'status' => true,
            'message' => 'Product deleted successfully'
        ]);
    }

    protected function slug($title)
    {
        $slug = Str::slug($title);
        $isProductExist = Product::where('slug', $slug)->first();
        if ($isProductExist) {
            $slug = $slug . '-' . rand(1000, 9999);
        }

        return $slug;
    }

    public function show($slug)
    {
        // Fetch the product using the slug
        $product = Product::with(['product_images', 'category', 'brand'])
            ->where('slug', $slug)
            ->where('status', 1)
            ->first(); // Use firstOrFail to throw a 404 if not found

        // Pass the product details to the view
        return view('front.product-detail', compact('product'));
    }

}