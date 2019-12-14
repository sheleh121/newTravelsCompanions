<?php

namespace App\Http\Controllers\Travels;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Travel;
use App\TravelUser;
use App\UserInfo;
use App\Http\Controllers\Chat\ChatRoomController;
use Auth;

class NewController extends Controller
{
    protected function create(Request $request) {

        $this->validate($request, [
            'name' => 'required|string|max:99|min:6',
            'country' => 'integer|min:1',
            'region' => 'integer|min:1',
            'city' => 'integer|min:1',
            'category' => 'required',
            'description' => 'required|min:20',
            'date_begin' => 'required|date|after:tomorrow',
            'date_end' => 'required|date|after:date_begin',
            'type' => 'required',
            'decision' => 'required',
            'commercial' => 'required'
        ]);

        $user_id = Auth::user()->id;

        $room_id = ChatRoomController::meetingNew($request['name']);
        $travel = Travel::create([
            'name' => $request['name'],
            'user_id' => $user_id,
            'country_id' => $request['country'],
            'region_id' => $request['region'],
            'city_id' => $request['city'],
            'travel_category_id' => $request['category'],
            'description' => $request['description'],
            'decision' => $request['decision'],
            'date_begin' => $request['date_begin'],
            'date_end' => $request['date_end'],
            'travel_type_id' => $request['type'],
            'commercial' => $request['commercial'],
            'room_id' => $room_id
        ]);
        TravelUser::create([
            'user_id' => $user_id,
            'travel_id' => $travel['id'],
            'claim' => 6
        ]);

        //Плюсик в карму за создание мероприятия
        $user = UserInfo::find($user_id);
        $user->karma += 50;
        $user->save();

        return response($travel->id, 200);
    }
}
