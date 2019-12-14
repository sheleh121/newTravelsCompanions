<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Images\ImageEditController;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\UserInfo;


class RegisterController extends Controller
{

    public function create(Request $data)
    {
        $this->validate($data, [
            'surname' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'patronymic' => 'required|string|max:255',
            'country' => 'integer|min:1',
            'region' => 'integer|min:1',
            'city' => 'integer|min:1',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'required|string|min:10|'
        ]);

        $phone = preg_replace('~[^0-9]+~','',$data['phone']);

        if (((substr($phone, 0, 1) == '7') || (substr($phone, 0, 1) == '8')) && (strlen($phone) > 10 )  ) {
            $phone = substr_replace($phone, '', 0, 1);
        }

        if (strlen($phone) < 10 ) {
            return response( [ 'errors' => ['phone' => 'Некорректный номер телефона']], 422);
        }

        if (User::wherePhone($phone)->first())
            return response([ 'errors' => ['phone' => 'На данный номер телефона уже зарегистрирован аккаунт, воспользуйтесь восстановлением пароля']], 422);

        $key_sms = random_int(131981, 999991);

        $user = User::create([
            'name' => $data['name'] . ' ' . $data['surname'],
            'phone' => $phone,
            'key_sms' => $key_sms,
            'password' => Hash::make($data['password']),
        ]);

        $image_id = ImageEditController::create(public_path('images/seeds/avatar_default.png'), $user->id);

        UserInfo::create([
            'id' => $user->id,
            'user_id' => $user->id,
            'surname' => $data['surname'],
            'name' => $data['name'],
            'patronymic' => $data['patronymic'],
            'country_id' => $data['country'],
            'region_id' => $data['region'],
            'city_id' => $data['city'],
            'image_id' => $image_id
        ]);

        // Внутренний API запрос для получения токенов
        $client = DB::table('oauth_clients')
            ->where('password_client', true)
            ->first();

        // Убедимся, что Password Client существует в БД (т.е. Laravel Passport
        // установлен правильно)
        if (!$client) {
            return response()->json([
                'message' => 'Passport is not setup properly.',
                'status' => 500
            ], 500);
        }

        $data = [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => $phone,
            'password' => $data['password'],
        ];

        $request = Request::create('/oauth/token', 'POST', $data);

        $response = app()->handle($request);

        // Проверяем был ли внутренний запрос успешным
        if ($response->getStatusCode() != 200) {
            return response()->json([
                'message' => 'Неверная пара логин и пароль',
                'status' => 422
            ], 422);
        }

        // Вытаскиваем данные из ответа
        $data = json_decode($response->getContent());

        //Подтверждение номера телефона
        ConfirmController::smsKey('Код подтверждения: ',$user);

        // Формируем окончательный ответ в нужном формате
        return response()->json([
            'token' => $data->access_token,
            'user' => $user,
            'status' => 200
        ]);


    }
}
