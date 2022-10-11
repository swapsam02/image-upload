<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Image;

class ImageController extends Controller
{
    public function imgUpload(Request $request)
    {
        $validate = Validator::make($request->all(),
        [
            'img_url' => 'required|mimes:jpeg,gif,png|max:500'
        ]);

        if($validate->fails())
        {
            return response()->json([
                'status' => false,
                'msg' => "error",
                'errors' => $validate->errors(),
            ], 401);
        }

        if($request->img_url)
        {
            $file = $request->file('img_url');
            $filename = time().'.'.$file->getClientOriginalExtension();
            $file->move('Images/', $filename);
            $input['img_url'] = $filename;

            Image::create($input);

            return response()->json([
                'status' => true,
                'msg' => "Image upload Successfully"
            ], 200);
        }
    }
}
