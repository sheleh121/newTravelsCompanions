<?php
/**
 * Created by PhpStorm.
 * User: s112
 * Date: 20.02.19
 * Time: 23:07
 */

namespace App\Http\Controllers\Images;
use App\Http\Controllers\Controller;

use App\Http\Controllers\Travels\TravelShowController;
use App\TravelUser;
use App\UserInfo;
use Illuminate\Http\Request;
use App\Image;
use Auth;


class ImageShowController extends Controller
{

    public function TravelImage($image_id, $travel_id, $size){
        $image = Image::select($size, 'decision')->whereIdAndTravel_id($image_id, $travel_id)->first();
        if (isset($image)){
            if (TravelShowController::decision($travel_id, $image['decision'])){
                return response($image[$size])->header('Content-Type', 'image/png');
            }
            else return response('Forbidden', 403);
        }
        else return response('Not Found	', 404);
    }

    public function TravelImageSmall(Request $request){
        return ImageShowController::TravelImage($request['image_id'], $request['travel_id'], 'image_small');
    }

    public function TravelImageFull(Request $request){
        return ImageShowController::TravelImage($request['image_id'], $request['travel_id'], 'image');
    }

    public function userAvatar(Request $request){
        if (Auth::check()){
            $image_id = UserInfo::select('image_id')->find($request['user_id']);
            $image = Image::select('image_small')->where('id', $image_id['image_id'])->first();

            return response($image['image_small'])->header('Content-Type', 'image/png');
        }
        else return response('Forbidden', 403);
    }

    public function TravelImages(Request $request){
        $user = Auth::user();
        $user_claim = TravelUser::select('claim')
            ->where('user_id', $user['id'])
            ->where('travel_id', $request['travel_id'])
            ->first();
        $decision = 1;

        if (isset($user)){
            $decision = 2;
            if ($user_claim['claim'] > 4)
                $decision = 3;
        }

        return Image::select('id', 'decision', 'travel_id')
            ->where('travel_id', $request['travel_id'])
            ->where('decision', '<=', $decision)
            ->orderBy('id', 'desc')
            ->simplePaginate(30);
    }



}