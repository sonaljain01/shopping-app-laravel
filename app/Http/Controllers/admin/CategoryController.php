<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Category;
use App\Models\TempImage;
use File;
use Intervention\Image\Laravel\Facades\Image;
class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::latest();

        if (!empty($request->get('keyword'))) {
            $categories = $categories->where('name', 'like', '%' . $request->get('keyword') . '%');
        }
        $categories = $categories->paginate(10);
        // dd($categories);

        return view('admin.category.list', compact('categories'));
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'nullable|unique:categories',
        ]);
        $slug = null;
        if ($request->slug) {
            $isCategoryExist = Category::where('slug', $request->slug)->first();
            if ($isCategoryExist) {
                return response()->json([
                    'status' => false,
                    'message' => 'Blog with this slug already exist',
                ]);
            } else {
                $slug = $request->slug;
            }
        } else {
            $slug = $this->slug($request->name);
        }
        
        if ($validator->passes()) {
            $category = new Category();
            $category->name = $request->name;
            $category->slug = $slug;
            $category->status = $request->status;
            $category->showHome = $request->showHome;
            $category->save();

            //save image
            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);

                $newImageName = $category->id . '.' . $ext;
                $sPath = public_path() . '/temp-images/' . $tempImage->name;
                $dPath = public_path() . '/uploads/category/' . $newImageName;
                File::copy($sPath, $dPath);

                // //Image thumbnail
                // $dPath = public_path() . '/uploads/category/thumb' . $newImageName;
                // $img = Image::make($sPath);
                // $img->resize(450,600);
                // $img->save($dPath);

                $category->image = $newImageName;
                $category->save();
            }

            $request->session()->flash('success', 'Category created successfully');
            return response()->json([
                'status' => true,
                'message' => 'Category created successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($categoryId, Request $request)
    {
        $category = Category::find($categoryId);
        if (empty($category)) {
            return redirect()->route('categories.index');
        }

        return view('admin.category.edit', compact('category'));
    }

    public function update($categoryId, Request $request)
    {
        $category = Category::find($categoryId);
        if (empty($category)) {
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Category not found'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories, slug, ' . $category->id . ',id',
        ]);

        if ($validator->passes()) {

            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->showHome = $request->showHome;
            $category->save();

            $oldImage = $category->image;

            //save image
            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);

                $newImageName = $category->id . '-' . time() . '.' . $ext;
                $sPath = public_path() . '/temp-images/' . $tempImage->name;
                $dPath = public_path() . '/uploads/category/' . $newImageName;
                File::copy($sPath, $dPath);

                // //Image thumbnail
                // $dPath = public_path() . '/uploads/category/thumb' . $newImageName;
                // $img = Image::make($sPath);
                // $img->resize(450,600);
                // $img->save($dPath);

                $category->image = $newImageName;
                $category->save();

                File::delete(public_path() . '/uploads/category/' . $oldImage);
            }

            $request->session()->flash('success', 'Category updated successfully');
            return response()->json([
                'status' => true,
                'message' => 'Category created successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($categoryId, Request $request)
    {
        $category = Category::find($categoryId);
        if (empty($category)) {
            return redirect()->route('categories.index');
        }

        File::delete(public_path() . '/uploads/category/' . $category->image);

        $category->delete();

        $request->session()->flash('success', 'Category deleted successfully');

        return response()->json([
            'status' => true,
            'message' => 'Category deleted successfully'
        ]);
    }

    protected function slug($title)
    {
        $slug = \Str::slug($title);
        $isCategoryExist = Category::where('slug', $slug)->first();
        if ($isCategoryExist) {
            $slug = $slug . '-' . rand(1000, 9999);
        }

        return $slug;
    }
}
