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
use Storage;
use DB;

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
        $product = Product::with('product_images')->findOrFail($id);

        // Validation rules
        $rules = [
            'title' => 'required|unique:products,title,' . $product->id,
            'slug' => 'required|unique:products,slug,' . $product->id,
            'price' => 'required|numeric',
            'sku' => 'required|unique:products,sku,' . $product->id,
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required',
            'is_featured' => 'required|in:Yes,No',
        ];

        // Additional validation if tracking quantity is enabled
        if ($request->track_qty === 'Yes') {
            $rules['qty'] = 'required|numeric';
        }

        // Validation
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

        // Begin transaction
        DB::beginTransaction();
        try {
            // Update product fields
            $product->update([
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

            // Handle images if any are uploaded
            if ($request->hasFile('image')) {
                $this->handleProductImages($product, $request->file('image'));
            }

            DB::commit();
            $request->session()->flash('success', 'Product Updated Successfully');
            return redirect()->route('products.index');
        } catch (\Exception $e) {
            // Rollback if any error occurs
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong. Please try again.',
            ]);
        }
    }

    /**
     * Handle product image upload and deletion.
     */
    private function handleProductImages(Product $product, $images)
    {
        // Delete old images
        foreach ($product->product_images as $image) {
            if (Storage::exists($image->image)) {
                Storage::delete($image->image);
            }
            $image->delete();
        }

        // Upload new images
        foreach ($images as $image) {
            $newName = time() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('uploads/product', $newName);

            ProductImage::create([
                'product_id' => $product->id,
                'image' => $path,
                'name' => $newName,
            ]);
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
            ->first();

        if (!$product) {
            abort(404);
        }
        // Pass the product details to the view
        return view('front.product-detail', compact('product'));
    }

    public function quickView($id)
    {
        $product = Product::with('product_images')->find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Load a view with product details for the quick view modal
        return view('front.quick-view-product', compact('product'));
    }


}