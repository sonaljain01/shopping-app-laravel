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
use App\Models\Attribute;
use App\Models\AttributeValue;


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
        $data['attributes'] = Attribute::all();
        return view('admin.products.create', $data);
    }

    public function store(Request $request)
    {
        // dd($request->all());


        $rules = [
            'title' => 'required|unique:products',
            'slug' => 'nullable|unique:products',
            'price' => 'required|numeric',
            'sku' => 'required',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required',
            'is_featured' => 'required|in:Yes,No',
            'description' => 'required',
            'attribute_name' => 'required|array',
            'attribute_value' => 'required|array',
            // 'attribute_name.*' => 'exists:attributes,id',
            // 'attribute_value.*' => 'exists:attribute_values,id',
            'image.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ];

        // Validate the form data
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            // dd($validator->errors());
            return redirect()->back()->withErrors($validator)->withInput();
        }
        DB::beginTransaction();
        try {
            // Create the product
            $product = Product::create([
                'title' => $request->title,
                'slug' => $request->slug ?: Str::slug($request->title),
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

            // foreach ($request->attribute_name as $key => $attribute_id) {
            //     $attribute_value_id = $request->attribute_value[$key];
            //     $product->attributes()->attach($attribute_id, ['attribute_value_id' => $attribute_value_id]);
            // }
            foreach ($request->attribute_name as $key => $attribute_id) {
                $attribute_value = $request->attribute_value[$key]; // Value for this attribute
                $attribute_value_id = AttributeValue::where('attribute_id', $attribute_id)
                    ->where('value', $attribute_value)
                    ->first()
                    ->id;
    
                // Attach the attribute and its value to the product
                $product->attributes()->attach($attribute_id, ['attribute_value_id' => $attribute_value_id]);
            }
            // Store product images if any
            if ($request->hasFile('image')) {

                foreach ($request->file('image') as $image) {
                    $ext = $image->getClientOriginalExtension();
                    $newName = time() . '-' . uniqid() . '.' . $ext;
                    $image->move(public_path('uploads/product'), $newName);

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => 'uploads/product/' . $newName,
                        'name' => $newName,
                    ]);
                }
            }
            DB::commit();
            return redirect()->route('products.index')->with('success', 'Product Created Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong');
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
        $attributes = Attribute::orderBy('name', 'ASC')->get();
        $data['attributes'] = $attributes;
        return view('admin.products.edit', $data);
    }



    public function update(Request $request, $id)
    {
        $product = Product::with('product_images', 'attributes')->findOrFail($id);

        // Validation rules
        $rules = [
            'title' => 'required|unique:products,title,' . $product->id,
            'slug' => 'required|unique:products,slug,' . $product->id,
            'price' => 'required|numeric',
            'sku' => 'required',
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

            // if ($request->has('attribute_name') && is_array($request->attribute_name)) {
            //     // Detach existing attributes
            //     $product->attributes()->detach();
    
            //     // Attach new attributes and their values
            //     foreach ($request->attribute_name as $index => $attribute_id) {
            //         $attribute_value = $request->attribute_value[$index];
            //         $attribute_value_id = AttributeValue::where('attribute_id', $attribute_id)
            //             ->where('value', $attribute_value)
            //             ->first()
            //             ->id;
    
            //         // Attach the new attribute-value relationship
            //         $product->attributes()->attach($attribute_id, ['attribute_value_id' => $attribute_value_id]);
            //     }
            // }   
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

            // Log the exception message for debugging
            \Log::error('Product update failed: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
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
        $product = Product::with(['product_images', 'category', 'brand', 'attributes.values'])
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