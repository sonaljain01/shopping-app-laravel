<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductImage;
use Intervention\Image\Facades\Image;

class ProductImageController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $image = $request->image;

        
        if(!empty($image)) {
            $ext = $image->getClientOriginalExtension();
            $newName = time().'.'.$ext;
            
            $productImage = new ProductImage();
            
            $productImage->image = $newName;
            $productImage->product_id = $request->product_id;
            $productImage->save();
            
            $image->move(public_path('uploads/product'), $newName);

            return response()->json([
                'status' => true,
                'image_id' => $productImage->id,
                'image_path' => asset('uploads/product' . $newName),
                'message' => 'Image uploaded successfully'
            ]);
        }
    }
}

    