<?php
/**
 * Created by PhpStorm.
 * User: s112
 * Date: 27.03.19
 * Time: 1:42
 */

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Travel;
use Illuminate\Http\Request;
use Auth;
use App\UserInfo;


class UserTravelsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function all(Request $request)
    {
        if (isset($request['user_id']))
            $user_id = $request['user_id'];
        else return response('', 400);

        $user = UserInfo::find($user_id);

        if (isset($user)) {
            if (Auth::user()->id === $user->user_id) {
                $travelsUser = $user->travelsUser()->with(
                    'claim'
                    ,'travel.city'
                    , 'travel.country'
                    , 'travel.region'
                    , 'travel.type'
                    , 'travel.category'
                    ,'travel.author'
                )->orderByDesc('updated_at')
                    ->simplePaginate(20);
            } else {
                //Возвращает travel null если decision == 3. Во фронте проверка на travel !== null перед выводом
                $travelsUser = $user->travelsUser()->with(
                    ['travel' => function ($query) {
                        $query->with(
                            'city'
                            , 'country'
                            , 'region'
                            , 'type'
                            , 'category'
                            ,'author'
                        )->where('decision', '!=', '3');
                    }]
                    ,'claim'
                )->where('travel_user.claim', '>', '4')
                    ->orderByDesc('updated_at')
                    ->simplePaginate(20);

            }
            if (isset($travelsUser))
                return response($travelsUser, 200);
        } else return response(trans('response.error.404'), 404);
    }

    public function author()
    {
        return response(Travel::where('user_id', Auth::id())->where('success', null)->get(['id', 'name']));
    }
}