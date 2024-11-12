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

        // $product = Product::find($id);

        // $data = [];
        // $data['product'] = $product;
        // $categories = Category::orderBy('name', 'ASC')->get();
        // $brands = Brand::orderBy('name', 'ASC')->get();
        // $data['categories'] = $categories;
        // $data['brands'] = $brands;
        // $attributes = Attribute::orderBy('name', 'ASC')->get();
        // $data['attributes'] = $attributes;
        // return view('admin.products.edit', $data);
        $product = Product::with(['attributes.values'])->find($id);

        if (!$product) {
            abort(404, 'Product not found');
        }

        // Prepare data for the view
        $data = [];
        $data['product'] = $product;
        $data['categories'] = Category::orderBy('name', 'ASC')->get();
        $data['brands'] = Brand::orderBy('name', 'ASC')->get();
        $data['attributes'] = Attribute::with('values')->orderBy('name', 'ASC')->get();

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

            if ($request->has('attributes') && is_array($request->attributes)) {
                // Sync attributes with the product
                $product->attributes()->sync($request->attributes); // This assumes attributes is an array of attribute IDs
            }
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
        $product = Product::with('product_images', 'attributes')->find($id);
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

        $product->attributes()->detach();
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

    public function bulkImportDashboard()
    {
        return view('admin.bulk-import');
    }

    public function downloadProductTemplate()
    {
        $headers = [
            'title',
            'slug',
            'price',
            'sku',
            'track_qty',
            'qty',
            'category_id',
            'brand_id',
            'description',
            'is_featured',
            'compare_price',
            'status',
            'barcode',
            'attribute_id',
            'attribute_value_id',
            'image_urls'
        ];
        return response()->streamDownload(function () use ($headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            fclose($file);
        }, 'product_template.csv');
        
    }

    // Download Categories CSV
    public function downloadCategories()
    {
        $categories = Category::select('id', 'name')->get();
        return $this->downloadCSV($categories, 'categories.csv');
    }

    // Download Brands CSV
    public function downloadBrands()
    {
        $brands = Brand::select('id', 'name')->get();
        return $this->downloadCSV($brands, 'brands.csv');
    }

    // Download Attributes CSV
    public function downloadAttributes()
    {
        $attributes = Attribute::select('id', 'name')->get();
        return $this->downloadCSV($attributes, 'attributes.csv');
    }

    // Helper function to download any CSV data
    private function downloadCSV($data, $filename)
    {
        return response()->streamDownload(function () use ($data) {
            $file = fopen('php://output', 'w');
            foreach ($data as $row) {
                fputcsv($file, $row->toArray());
            }
            fclose($file);
        }, $filename);
    }

    // Handle bulk product import

    public function importProducts(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:10240', // 10MB max file size
        ]);

        // Open the uploaded file
        $file = $request->file('file');
        $handle = fopen($file->getRealPath(), 'r');

        // Check if the file was opened successfully
        if ($handle === false) {
            return redirect()->route('admin.bulk-import')->with('error', 'Failed to open the CSV file.');
        }

        // Read the first row as the header
        $header = fgetcsv($handle);

        // Loop through each row in the CSV file
        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row); // Combine headers with row values

            // Check if category and brand exist or create them
            $category = Category::find($data['category_id']);
            $brand = Brand::find($data['brand_id']);

            if (!$category) {
                $category = Category::create(['name' => $data['category_name']]); // You can adjust this logic
            }

            if (!$brand) {
                $brand = Brand::create(['name' => $data['brand_name']]); // Adjust as needed
            }

            // Create the product
            $product = Product::create([
                'title' => $data['title'],
                'slug' => Str::slug($data['title']),
                'price' => $data['price'],
                'sku' => $data['sku'],
                'track_qty' => $data['track_qty'] ?? 0,
                'qty' => $data['qty'] ?? 0,
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'description' => $data['description'],
                'is_featured' => $data['is_featured'] ?? 'No',
                'compare_price' => $data['compare_price'] ?? 0,
                'status' => $data['status'] ?? '1',
                'barcode' => $data['barcode'] ?? null,
            ]);

            if (!empty($data['attribute_ids']) && !empty($data['attribute_value_ids'])) {
                $attributeIds = explode(',', $data['attribute_ids']); // Multiple attribute IDs
                $attributeValueIds = explode(',', $data['attribute_value_ids']); // Multiple attribute value IDs

                foreach ($attributeIds as $index => $attributeId) {
                    $attribute = Attribute::find($attributeId);
                    if ($attribute) {
                        // Find or create the attribute value
                        $attributeValue = AttributeValue::find($attributeValueIds[$index]);

                        if ($attributeValue) {
                            // Attach the attribute to the product with the corresponding attribute value
                            $product->attributes()->attach($attribute, ['attribute_value_id' => $attributeValue->id]);
                        }
                    }
                }
            }

            if (!empty($data['image_urls'])) {
                $imageUrls = explode(',', $data['image_urls']); // Comma-separated image URLs in CSV
                foreach ($imageUrls as $imageUrl) {
                    // Assuming the images are stored in a directory like 'uploads/product/filename.jpg'
                    // Here, we are storing the relative path (e.g., 'uploads/product/image.jpg') in the database.
                    $imagePath = 'uploads/product/' . trim($imageUrl); // Adjust the path if necessary

                    // Create the image entry in the product_images table
                    $product->product_images()->create([
                        'image' => $imagePath, // Save the relative image path
                    ]);
                }
            }

        }

        fclose($handle);

        return redirect()->route('products.index')->with('success', 'Products imported successfully!');
    }

    public function downloadAttributeValues()
    {
        $attributeValues = AttributeValue::select('id', 'value', 'attribute_id')->get();
        return $this->downloadCSV($attributeValues, 'attribute_values.csv');
    }
}