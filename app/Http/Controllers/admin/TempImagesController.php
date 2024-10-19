<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TempImage;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class TempImagesController extends Controller
{
    public function create(Request $request)
    {
        $image = $request->image;

        if(!empty($image)) {
            $ext = $image->getClientOriginalExtension();
            $newName = time().'.'.$ext;

            $tempImage = new TempImage();

            $tempImage->name = $newName;
            $tempImage->save();

            $image->move(public_path('temp-images'), $newName);


            // generate thumbnail
            $sourcePath = public_path() . '/temp-images' . $newName;
            // $destPath = public_path() . '/temp-images/thumb' . $newName;
            // $image = Image::make($sourcePath)->resize(300,275);
            $imgManager = new ImageManager(new Driver);
            $thumbImage = $imgManager->read('temp-images/' . $newName);
            $thumbImage->resize(300,275);
            $thumbImage->save(public_path('temp-images/thumb'. $newName));

            return response()->json([
                'status' => true,
                'image_id' => $tempImage->id,
                'ImagePath' => asset('temp-images/thumb' . $newName),
                'message' => 'Image uploaded successfully'
            ]);

        }
    }
}
