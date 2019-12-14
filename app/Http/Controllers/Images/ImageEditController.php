<?php
/**
 * Created by PhpStorm.
 * User: s112
 * Date: 20.02.19
 * Time: 23:08
 */

namespace App\Http\Controllers\Images;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Image;
use Illuminate\Support\Facades\Auth;
use Imagick;



class ImageEditController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public static function AddForTravel(Request $request) {
        $image_big = new Imagick();
        $image_small = new Imagick();

        $image_big->readImage($request->file('image'));
        if ($image_big->getImageWidth() > 1024)
            $image_big->thumbnailImage(1024,0);

        $image_small->readImage($request->file('image'));
        $image_small->thumbnailImage(300,0);
        if ($image_small->getImageHeight() > 200)
            $image_small->cropImage(300, 200, 0, 0);

        return Image::create([
            'travel_id' => $request['travel_id'],
            'decision' => 3,
            'image_small' => $image_small,
            'image' => $image_big,
            'user_id' => Auth::user()->id
        ]);
    }

    public static function create($file, $user_id) {
        $image_big = new Imagick();
        $image_small = new Imagick();

        $image_big->readImage($file);
        if ($image_big->getImageWidth() > 1024)
            $image_big->thumbnailImage(1024,0);

        $image_small->readImage($file);

        $d = $image_small->getImageGeometry();
        $src_width = $d['width'];
        $src_height = $d['height'];
        if ($src_width < $src_height) {
            $image_small->cropImage($src_width, $src_width, 0, (($src_height - $src_width)/2));
        } else {
            $image_small->cropImage($src_height, $src_height, (($src_width - $src_height)/2), 0);
        }

        $image_small->thumbnailImage(200,200);

        $image_id = Image::create([
            'decision' => 3,
            'image_small' => $image_small,
            'image' => $image_big,
            'user_id' => $user_id
        ]);
        return $image_id->id;
    }

    public function Delete(Request $request){
        $image = Image::find($request['image_id']);
        if (Auth::user()->id == $image['user_id']){
            $image->delete();
        }
    }

    public function UpdateDecision(Request $request){
        $image = Image::find($request['image_id']);
        if (Auth::user()->id == $image['user_id']){
            $image['decision'] = $request['decision'];
            $image->save();
        }
    }



}


