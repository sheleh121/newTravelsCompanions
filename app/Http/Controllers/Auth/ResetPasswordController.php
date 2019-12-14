<?php

namespace App\Http\Controllers\Auth;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Auth;

class ResetPasswordController extends Controller
{
    public function reset(Request $request) {
        $this->validate($request, [
            'password' => 'required|string|min:6|confirmed',
            'password_current' => 'required|string|min:6',
        ]);

        $user = Auth::user();
        if (!Hash::check($request['password_current'], $user->password)) {
            return response('Неверный пароль', 422);
        }
        else {
            $user->password = Hash::make($request['password']);
            $user->save();
            return response('Пароль изменен', 200);
        }
    }
}
