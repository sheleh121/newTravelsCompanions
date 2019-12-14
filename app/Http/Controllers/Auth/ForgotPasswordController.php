<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Images\ImageEditController;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\UserInfo;


class ForgotPasswordController extends Controller
{

    public function forgot(Request $data)
    {
        $phone = preg_replace('~[^0-9]+~','',$data['phone']);
        if ((substr($phone, 0, 1) !== '7') && (substr($phone, 0, 1) == '8') )
            $phone = substr_replace($phone, 7, 0, 1);

        if (User::where('phone', $phone)->first()) {
            $user = User::where('phone', $phone)->first();



            if ($user->phone == $phone) {
                if (strtotime($user->updated_at) +600 < strtotime('now')) {

                    $symbols = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $password = '';
                    $max = mb_strlen($symbols, '8bit') - 1;
                    for ($i = 0; $i < 9; ++$i) {
                        $password .= $symbols[random_int(0, $max)];
                    }

                    $user->key_sms = $password;
                    $user->password = Hash::make($password);
                    $user->save();

                    ConfirmController::smsKey('Пароль: ', $user);
                    return response('Вам выслан новый пароль', 200);
                }
                else return response('Запросить новый код можно не более одного раза в десять минут', 422);
            }
        }
        return response( 'Такого пользователя не существует', 422);


    }
}
