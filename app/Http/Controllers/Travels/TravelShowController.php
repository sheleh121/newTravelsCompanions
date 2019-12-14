<?php

namespace App\Http\Controllers\Travels;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Travel;
use App\TravelCategory;
use App\TravelType;
use App\TravelUser;
use Auth;



class TravelShowController extends Controller
{

    public static function decision ($travel_id, $decision){
        $user = Auth::user();
        if(isset($user)){
            $user_claim = TravelUser::select('claim')
                ->where('user_id', $user->id)
                ->where('travel_id', $travel_id)
                ->first();
        } else {
            $user_claim = 1;
        }

        if (($decision == 3 && $user_claim->claim < 5) || ($decision == 2 && isset($user) == false)){
            return false;
        }
        else return true;
    }

    public function Types(){
        return travelType::select('id', 'name')->get();
    }

    public function Categories(){
        return travelCategory::select('id', 'name')->get();
    }

    public function travels(Request $request) {

        $this->validate($request, [
            'country' => 'integer',
            'region' => 'integer',
            'city' => 'integer',
            'category' => 'integer',
            'type' => 'integer',
            'decision' => 'integer',
            'date_begin' => 'date',
            'date_end' => 'date',

        ]);

        if (Auth::check()) $decision = 2;
        else $decision = 1;

        $where[] = ['decision', '<=', $decision];

        foreach($request->request as $id => $value){
            if ($id !== 'page') {
                if ($id == 'date_begin')
                    $where[] = [$id, '>=', $value];
                elseif ($id == 'date_end')
                    $where[] = [$id, '<=', $value];
                elseif ($id == 'commercial') {
                    if ($value == false)
                        $where[] = [$id, '=', $value];
                }
                else
                    $where[] = [$id, '=', $value];
            }
        }

        $travels = Travel::with(
            'city'
            , 'country'
            , 'region'
            , 'type'
            , 'category'
            ,'author'
        )->where($where)
            ->orderByDesc('updated_at')
            ->simplePaginate(20);

        return response($travels, 200);
    }

    public function travelAllUsers(Request $request){

        $all_travel_users = Traveluser::with(
            'userInfo'
            ,'claim'
        )->where('travel_id', $request['travel_id'])
            ->orderBy('claim')
            ->get();

        if(TravelShowController::decision($request['travel_id'], 3)){
            return response($all_travel_users, 200);
        }
        else return response(trans('response.error.404'), 404);

    }

    public function travel($travel_id)
    {

        $user_id = Auth::id();

        $travel = Travel::with(
            'city'
            , 'country'
            , 'region'
            , 'type'
            , 'category'
            ,'author'
        )->find($travel_id);

        if (isset($travel) == false)
            return response(trans('response.error.404'), 404);
        else {
            $travel_user = $travel->travelUsers()->where('user_id', $user_id)->first();
            if (isset($travel_user)) {
                $current_user_claim = $travel_user;
            } else {
                $current_user_claim['claim'] = 0;
            }

            if (TravelShowController::decision($travel_id, $travel->decision)){
                return response([
                    'travel' => $travel
                    ,'current_user_claim' => $current_user_claim
                ], 200);
            } else if (Auth::check()) {
                response('response.error.403', 403);
            } else {
                return response(trans('response.error.401'), 401);
            }
        }
    }


}
