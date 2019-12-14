<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Auth;
use App\UserInfo;
use App\Http\Controllers\Images\ImageEditController;



class UserEditController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }


    public function location(Request $request)
    {
        $this->validate($request, [
            'country' => 'integer|min:1',
            'region' => 'integer|min:1',
            'city' => 'integer|min:1',
        ]);

        $user  = UserInfo::find(Auth::id());
        $user->country_id = $request['country'];
        $user->region_id = $request['region'];
        $user->city_id = $request['city'];
        if ($user->save())
            return response(trans('response.success.200'), 200);
        else return response(trans('response.error.422'), 422);
    }

    public function fio(Request $request)
    {
        $this->validate($request, [
            'surname' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'patronymic' => 'required|string|max:255',
        ]);

        $user  = UserInfo::find(Auth::id());

        $user->surname = $request['surname'];
        $user->name = $request['name'];
        $user->patronymic = $request['patronymic'];
        if ($user->save())
            return response(trans('response.success.200'), 200);
        else return response(trans('response.error.422'), 422);

    }

    public function email(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string|email|max:45|',
        ]);

        if (User::whereEmail($request['email'])->first())
            return response( 'e-mail уже зарегистрирован в системе', 422);

        $user  = Auth::user();
        $user->email = $request['email'];
        if ($user->save())
            return response('E-Mail успешно изменен', 200);
        else return response(trans('response.error.422'), 422);

    }

    public function phone(Request $request)
    {
        /*$this->validate($request, [
            'phone' => 'required|string|min:10|'
        ]);

        $phone = preg_replace('~[^0-9]+~','',$request['phone']);
        if ((substr($phone, 0, 1) !== '7') && (substr($phone, 0, 1) == '8') ) {
            $phone = substr_replace($phone, 7, 0, 1);
        }

        if (User::wherePhone($phone)->first())
            return response('На данный номер телефона уже зарегистрирован аккаунт', 422);
        else {
            $user  = Auth::user();
            $key_sms = random_int(131981, 999991);

            $user->phone = $phone;
            $user->key_sms = $key_sms;
            $user->locked = 1;
            $user->save();

            if (ConfirmController::smsKey($user))
                return response('Вам отправлен код подтверждения', 200);
            else return response(trans('response.error.422'), 422);
        }*/
        return response('НЕ реализовано', 501);

    }

    public function avatar(Request $request)
    {
        $user  = UserInfo::find(Auth::id());
        $file = $request->file('image');
        $image_id = ImageEditController::create($file, $user->id);

        $user->image_id = $image_id;
        if ($user->save())
            return response(trans('response.success.200'), 200);
        else return response(trans('response.error.422'), 422);
        //return response('НЕ реализовано', 501);
    }


}
