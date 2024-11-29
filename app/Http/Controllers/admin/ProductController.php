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
use App\Models\Menu;
use App\Models\City;

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
            'length' => 'required|numeric',
            'breath' => 'required|numeric',
            'height' => 'required|numeric',

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
                'length' => $request->length,
                'breath' => $request->breath,
                'height' => $request->height
            ]);


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
            'length' => 'required|numeric',
            'breath' => 'required|numeric',
            'height' => 'required|numeric',

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
                'length' => $request->length,
                'breath' => $request->breath,
                'height' => $request->height
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
        $headerMenus = Menu::with([
            'children' => function ($query) {
                $query->where('status', 1)
                    ->with([
                        'children' => function ($query) {
                            $query->where('status', 1)
                                ->with([
                                    'children' => function ($query) {
                                        $query->where('status', 1);
                                    }
                                ]);
                        }
                    ]);
            }
        ])
            ->whereNull('parent_id') // Ensure only top-level menus are fetched
            ->where('status', 1) // Only include menus with status = 1
            ->where(function ($query) {
                $query->where('location', 'header')
                    ->orWhere('location', 'both');
            })
            ->get();

        // Optionally, for the footer menus, you can follow the same approach
        $footerMenus = Menu::with([
            'children' => function ($query) {
                $query->where('status', 1)
                    ->with([
                        'children' => function ($query) {
                            $query->where('status', 1)
                                ->with([
                                    'children' => function ($query) {
                                        $query->where('status', 1);
                                    }
                                ]);
                        }
                    ]);
            }
        ])
            ->whereNull('parent_id')
            ->where('status', 1) // Only include menus with status = 1
            ->where(function ($query) {
                $query->where('location', 'footer')
                    ->orWhere('location', 'both');
            })
            ->get();
        // Pass the product details to the view
        return view('front.product-detail', compact('product', 'headerMenus', 'footerMenus'));
    }

    public function quickView($id)
    {
        $product = Product::with('product_images')->find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }


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
            'attribute_ids',
            'attribute_value_ids',
            'image_urls',
            'length',
            'breath',
            'height',
        ];
        return response()->streamDownload(function () use ($headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            fclose($file);
        }, 'product_template.csv');

    }


    public function downloadCategories()
    {
        $categories = Category::select('id', 'name')->get();
        $headers = [
            'id',
            'name'
        ];
        return response()->streamDownload(function () use ($categories, $headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            foreach ($categories as $category) {
                fputcsv($file, $category->toArray());
            }
            fclose($file);
        }, 'categories.csv');
    }


    public function downloadBrands()
    {
        $brands = Brand::select('id', 'name')->get();
        $headers = [
            'id',
            'name'
        ];
        return response()->streamDownload(function () use ($brands, $headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            foreach ($brands as $brand) {
                fputcsv($file, $brand->toArray());
            }
            fclose($file);
        }, 'brands.csv');
    }


    public function downloadAttributes()
    {
        $attributes = Attribute::select('id', 'name')->get();
        $headers = [
            'id',
            'name'
        ];

        return response()->streamDownload(function () use ($attributes, $headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            foreach ($attributes as $attribute) {
                fputcsv($file, $attribute->toArray());
            }
            fclose($file);
        }, 'attributes.csv');

    }


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

    public function importProducts(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:10240',
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getRealPath(), 'r');

        if ($handle === false) {
            return redirect()->route('admin.bulk-import')->with('error', 'Failed to open the CSV file.');
        }

        $header = fgetcsv($handle);
        $duplicateEntries = [];

        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);
            if (!isset($data['title']) || empty($data['title'])) {
                continue; // Skip if 'title' is missing or empty
            }
            $slug = Str::slug($data['title']);

            if ($this->isDuplicateProduct($data['title'], $slug)) {
                $duplicateEntries[] = $data['title'];
                continue; // Skip duplicate entry
            }

            $category = Category::find($data['category_id']);
            $brand = Brand::find($data['brand_id']);

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

            $this->attachAttributes($product, $data['attribute_id'], $data['attribute_value_id']);
            $this->addProductImages($product, $data['image_urls']);
        }

        fclose($handle);

        $message = 'Products imported successfully!';
        if (!empty($duplicateEntries)) {
            $message .= ' However, some products were skipped due to duplicate titles: ' . implode(', ', $duplicateEntries);
        }

        return redirect()->route('products.index')->with('success', $message);
    }

    protected function isDuplicateProduct($title, $slug)
    {
        return Product::where('title', $title)->orWhere('slug', $slug)->exists();
    }

    protected function findOrCreateCategory($id, $name)
    {
        return Category::firstOrFail(['id' => $id]);
    }

    protected function findOrCreateBrand($id, $name)
    {
        return Brand::firstOrCreate(['id' => $id], ['name' => $name]);
    }

    protected function attachAttributes(Product $product, $attributeIds, $attributeValueIds)
    {
        if (!empty($attributeIds) && !empty($attributeValueIds)) {
            $attributeIds = explode(',', $attributeIds);
            $attributeValueIds = explode(',', $attributeValueIds);

            $attributesData = [];
            foreach ($attributeIds as $index => $attributeId) {
                if (!empty($attributeValueIds[$index])) {
                    $attributesData[$attributeId] = ['attribute_value_id' => $attributeValueIds[$index]];
                }
            }

            $product->attributes()->attach($attributesData);
        }
    }

    protected function addProductImages(Product $product, $imageUrls)
    {
        if (!empty($imageUrls)) {
            $imageUrls = explode(',', $imageUrls);

            $imageData = array_map(function ($imageUrl) {
                return ['image' => 'uploads/product/' . trim($imageUrl)];
            }, $imageUrls);

            $product->product_images()->createMany($imageData);
        }
    }

    public function downloadAttributeValues()
    {
        $attributeValues = AttributeValue::select('id', 'value', 'attribute_id')->get();
        $headers = [
            'id',
            'value',
            'attribute_id',
        ];

        return response()->streamDownload(function () use ($attributeValues, $headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            foreach ($attributeValues as $attributeValue) {
                fputcsv($file, $attributeValue->toArray());
            }
            fclose($file);
        }, 'attribute_values.csv');
    }

}