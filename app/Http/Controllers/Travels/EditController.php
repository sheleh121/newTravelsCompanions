<?php

namespace App\Http\Controllers\Travels;
use App\Events\Travels\TravelNoticeEvent;
use App\Http\Controllers\Controller;


use Illuminate\Http\Request;
use App\Travel;
use App\UserInfo;
use Auth;

class EditController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit(Request $request){
        $travel = Travel::find($request['travel_id']);

        if (Auth::id() == $travel->user_id){
            if (isset($travel->success) == false){
                $this->validate($request, [
                    'category' => 'required',
                    'description' => 'required|min:20',
                    'date_begin' => 'required|date|after:tomorrow',
                    'date_end' => 'required|date|after:date_begin',
                    'type' => 'required',
                    'decision' => 'required'
                ]);

                $travel->description = $request['description'];
                $travel->decision = $request['decision'];
                $travel->date_begin = $request['date_begin'];
                $travel->date_end = $request['date_end'];
                $travel->travel_type_id = $request['type'];
                $travel->travel_category_id = $request['category'];
                $travel->save();

                $travelUsers = $travel->travelUsers()->where('claim', '>', 4)->get();
                foreach ($travelUsers as $travelUser) {
                    TravelNoticeEvent::dispatch(trans('travel.edit.edit'), $travelUser->user_id, $travel->id);
                }
                return response(trans('response.success.201'), 201);
            }
        }
        return response(trans('response.error.404'), 404);
    }

    public function success(Request $request) {
        //$travel['success'] true = состоялось, false = отменено, null = активное
        $travel = Travel::find($request['travel_id']);
        if (Auth::check()){
            if (Auth::id() == $travel->user_id){
                $travel->success = $request['success'];
                $travel->save();
                $travelUsers = $travel->travelUsers()->where('claim', '>', 4)->get();
                if ($request['success']) {
                    foreach ($travelUsers as $travelUser) {
                        $user = $travelUser->userInfo;
                        if ($user->user_id == Auth::id()) $user->karma += 100;
                        $user->karma += 20;
                        $user->save();
                        TravelNoticeEvent::dispatch(trans('travel.edit.success.success'), $travelUser->user_id, $travelUser->travel_id);
                    }
                    return response(trans('travel.success'), 201);
                }
                else {
                    $user = UserInfo::find($travel->user_id);
                    $user->karma -= 100;
                    $user->save();
                    foreach ($travelUsers as $travelUser) {
                        TravelNoticeEvent::dispatch(trans('travel.edit.success.reject'), $travelUser->user_id, $travelUser->travel_id);
                    }
                    return response(trans('travel.not_success'), 201);
                }
            }
            else return response(trans('response.error.403'), 403);
        }
        else return response(trans('response.error.401'), 401);
    }

}
