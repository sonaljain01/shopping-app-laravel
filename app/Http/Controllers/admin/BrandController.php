<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Brand;

class BrandController extends Controller
{

    public function index(Request $request)
    {
        $brands = Brand::latest('id');
        if (!empty($request->get('keyword'))) {
            $categories = $brands->where('name', 'like', '%' . $request->get('keyword') . '%');
        }
        $brands = $brands->paginate(10);
        return view('admin.brands.list', compact('brands'));
    }
    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'status' => 'required',
        ]);

        if($validator->passes()){
            $brand = new Brand();
            $brand->name = $request->name;
            $brand->status = $request->status;
            $brand->save();

            $request->session()->flash('success', 'Brand created successfully');
            return redirect()->route('brands.index');
        } else {

            $request->session()->flash('error', $validator->errors()->first());
            return redirect()->route('brands.index');
        }
    }
    public function destroy($brandId, Request $request)
    {
        $brand = Brand::find($brandId);
        if(empty($brand)) {
            return redirect()->route('brands.index');
        }

        $brand->delete();

        $request->session()->flash('success', 'Brand deleted successfully');

        return response()->json([
            'status' => true,
            'message' => 'Brand deleted successfully'
        ]);
    }

    public function edit($BrandId, Request $request)
    {
        $brand = Brand::find($BrandId);
        if(empty($brand)) {
            return redirect()->route('brands.index');
        }
        
        return view('admin.brands.edit', compact('brand'));
    }

    public function update($BrandId, Request $request)
    {
        $brand = Brand::find($BrandId);
        if(empty($brand)) {
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'brand not found'
            ]);
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->passes()) {

            $brand->name = $request->name;
            $brand->status = $request->status;
            $brand->save();

           
    
            $request->session()->flash('success', 'brand updated successfully');
            return response()->json([
                'status' => true,
                'message' => 'brand updated successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
}
