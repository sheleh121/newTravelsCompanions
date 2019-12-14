<?php

namespace App\Http\Controllers\Travels;

use App\Events\Travels\TravelInviteEvent;
use App\Events\Travels\TravelNoticeEvent;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Travel;
use App\TravelUser;
use App\User;
use Auth;


class TravelUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    protected function claim(Request $request)
    {
        $user_id = Auth::user()->id;
        $travel = Travel::find($request['travel_id']);
        $claim = TravelUser::where('travel_id', $travel->id)->where('user_id', $user_id)->where('claim', '<>', 6)->first();
        //Проверяем что заявка не была подана ранее и пользователь не является автором
        if (isset($claim)){
            //Если была отклонена пользователем, то позволим ему перейти в подавшие заявки
            if ($claim->claim == 2){
                $claim->claim = 3;
                $claim->save();
                return response(trans('travel.claim.claim.success'), 201);
            }
            return response(trans('response.error.404'), 404);
        }
        TravelUser::create([
            'user_id' => $user_id,
            'travel_id' => $request['travel_id'],
            'claim' => 3
        ]);
        TravelNoticeEvent::dispatch(trans('travel.claim.claim.notice'), $travel->user_id, $travel->id);
        return response(trans('travel.claim.claim.success'), 201);
    }

    protected function invite(Request $request)
    {
        $claim = TravelUser::where('travel_id', $request['travel_id'])->where('user_id', $request['user_invited'])->first();
        if (isset($claim))
            //return 'Приглашение можно выдать не более одного раза';
            return response(trans('travel.claim.invite.error'), 422);
        $travel = Travel::find($request['travel_id']);
        if ($travel->user_id == Auth::user()->id){
            $travel_user = TravelUser::create([
                'user_id' => $request['user_invited'],
                'travel_id' => $request['travel_id'],
                'claim' => 4
            ]);
            TravelInviteEvent::dispatch($travel_user->id);
            TravelNoticeEvent::dispatch(trans('travel.claim.invite.notice'), $travel_user->user_id, $travel_user->travel_id);
            return response(trans('travel.claim.invite.success'), 201);
        }
        return response(trans('response.error.404'), 404);
    }

    protected function update(Request $request)
    {
        $auth_user_id = Auth::user()->id;
        $claim = TravelUser::find($request['claim_id']);
        $new_claim = $request['new_claim'];

        if (isset($claim)){

            $user = User::find($claim->user_id);
            $travel = Travel::find($claim->travel_id);

            if ($auth_user_id == $travel->user_id){
                if($new_claim == 5 && (($user->chatRooms->contains($travel->room_id)) != true) ) {
                    TravelNoticeEvent::dispatch(trans('travel.claim.update.claim.success'), $claim->user_id, $travel->id);
                    $user->chatRooms()->attach($travel->room_id);
                }
                if($new_claim < 5 && $claim['claim'] == 5) {
                    TravelNoticeEvent::dispatch(trans('travel.claim.update.claim.reject'), $claim->user_id, $travel->id);
                    $user->chatRooms()->detach($travel->room_id);
                }
                $claim->claim = $new_claim;
                $claim->save();
                return response(trans('travel.claim.update.request'), 201);
            }
            elseif (($auth_user_id == $claim->user_id) && (($claim->claim == 4) || ($new_claim == 2))){
                if($new_claim == 5 && (($user->chatRooms->contains($travel->room_id)) != true) ) {
                    TravelNoticeEvent::dispatch(trans('travel.claim.update.claim.success'), $travel->user_id, $travel->id);
                    $user->chatRooms()->attach($travel->room_id);
                }
                if($new_claim < 5) {
                    TravelNoticeEvent::dispatch(trans('travel.claim.update.claim.reject'), $travel->user_id, $travel->id);
                    if ($claim->claim == 5)
                        $user->chatRooms()->detach($travel->room_id);
                }
                $claim->claim = $new_claim;
                $claim->save();
                return response(trans('travel.claim.update.response'), 201);
            }
        }
        return response(trans('response.error.404'), 404);
    }
}
