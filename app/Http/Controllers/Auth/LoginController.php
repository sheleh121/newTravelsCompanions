<?php

namespace App\Http\Controllers\Auth;


use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login()
    {
        $phone = preg_replace('~[^0-9]+~','',request('username'));

        if (((substr($phone, 0, 1) == '7') || (substr($phone, 0, 1) == '8')) && (strlen($phone) > 10 )  ) {
            $phone = substr_replace($phone, '', 0, 1);
        }



        $user = User::where('phone', $phone)->first();

        if (!$user) {
            return response('Неверная пара логин пароль 3' . $phone, 422);
        }

        if (!Hash::check(request('password'), $user->password)) {
            return response('Неверная пара логин пароль 1', 422);
        }

        // Внутренний API запрос для получения токенов
        $client = DB::table('oauth_clients')
            ->where('password_client', true)
            ->first();

        // Убедимся, что Password Client существует в БД (т.е. Laravel Passport
        // установлен правильно)
        if (!$client) {
            return response('Ошибка на сервере. Клиент не существует', 500);
        }

        $data = [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => $phone,
            'password' => request('password'),
        ];

        $request = Request::create('/oauth/token', 'POST', $data);

        $response = app()->handle($request);

        // Проверяем был ли внутренний запрос успешным
        if ($response->getStatusCode() != 200) {
            return response('Неверная пара логин пароль 2' . $response      , 422);
        }

        // Вытаскиваем данные из ответа
        $data = json_decode($response->getContent());

        // Формируем окончательный ответ в нужном формате
        return response()->json([
            'token' => $data->access_token,
            'user' => $user,
            'status' => 200
        ]);
    }

    public function logout()
    {
        $accessToken = Auth::user()->token();

        $refreshToken = DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true
            ]);

        $accessToken->revoke();

        return response()->json(['status' => 200]);

    }
}
