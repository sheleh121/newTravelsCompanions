<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\UserInfo;



class UserShowController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function user(Request $request) {
        return response(UserInfo::with('city', 'country', 'region')->find($request['user_id']));
    }

    public function currentUser() {
        $user = Auth::user();
        $user_info = $user->userInfo()->first();
        return response([
            'surname' => $user_info->surname,
            'name' => $user_info->name,
            'patronymic' => $user_info->patronymic,
            'image' => $user_info->image_id,
            'country' => $user_info->country_id,
            'region' => $user_info->region_id,
            'city' => $user_info->city_id,
            'email' => $user->email,
            'phone' => $user->phone
        ], 200);
    }

    public function users(Request $request)
    {
        $where = array();
        if (isset($request['country'])) {
            if ($request['country'] != 0 && is_numeric($request['country'])) {
                $where[] = ['users_info.country_id', '=', $request['country']];
            }
        }

        if (isset($request['region'])) {
            if ($request['region'] != 0 && is_numeric($request['region'])) {
                $where[] = ['users_info.region_id', '=', $request['region']];
            }
        }

        if (isset($request['city'])) {
            if ($request['city'] != 0 && is_numeric($request['city'])) {
                $where[] = ['users_info.city_id', '=', $request['city']];
            }
        }

        $users = UserInfo::with(
            'city'
            , 'country'
            , 'region'
        )->where($where)
            ->orderByDesc('karma')
            ->simplePaginate(20);

        return response($users, 200);
    }

}
