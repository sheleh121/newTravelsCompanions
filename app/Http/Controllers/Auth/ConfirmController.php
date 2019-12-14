<?php
/**
 * Created by PhpStorm.
 * User: s112
 * Date: 03.04.19
 * Time: 21:42
 */

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use stdClass;


class ConfirmController extends Controller
{
    public function user()
    {
        return response(Auth::user(), 200);
    }

    public function confirm(Request $request) {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->locked == 1){
                if (strtotime($user->updated_at) + 600 > strtotime('now') ) {
                    if ($request['key_sms'] == $user->key_sms) {
                        $user->locked = 0;
                        $user->save();
                        return response($user, 200);
                    }
                    else return response([ 'errors' => ['key_sms' => 'Неверный код подтверждения']], 422);
                }
                else return response([ 'errors' => ['key_sms' => 'Срок действия кода подтверждения истек, запросите новый']], 422);
            }
            else return response([ 'errors' => ['key_sms' => trans('response.error.401')]], 401);
        }
        else return response([ 'errors' => ['key_sms' => trans('response.error.401')]], 401);
    }

    public function getSms () {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->locked == 1){
                if (strtotime($user->updated_at) +600 < strtotime('now')) {
                    $key_sms = random_int(131981, 999991);
                    $user->key_sms = $key_sms;
                    $user->save();
                    ConfirmController::smsKey('Код подтверждения: ', $user);
                    return response('Код подтверждения отправлен', 200);
                }
                else return response([ 'errors' => ['key_sms' => 'Запросить новый код можно не более одного раза в десять минут']], 422);
            }
            else return response([ 'errors' => ['key_sms' => trans('response.error.401')]], 401);
        }
        else return response([ 'errors' => ['key_sms' => trans('response.error.401')]], 401);
    }

    public static function smsKey ($message, $user) {
        if (isset($user->phone)){
            $fd = fopen("../storage/logs/sms.log", 'a+');
            $smsru =new SMSRU($_ENV['SMSRU_API_KEY']); // Ваш уникальный программный ключ, который можно получить на главной странице

            $data = new stdClass();
            $data->to = '+' . $user->phone;
            $data->text = 'travels-companions.info '. $message . ' ' . $user->key_sms; // Текст сообщения
            $data->from = 'travels-com'; // Если у вас уже одобрен буквенный отправитель, его можно указать здесь, в противном случае будет использоваться ваш отправитель по умолчанию
            // $data->time = time() + 7*60*60; // Отложить отправку на 7 часов
            // $data->translit = 1; // Перевести все русские символы в латиницу (позволяет сэкономить на длине СМС)
            // $data->test = 1; // Позволяет выполнить запрос в тестовом режиме без реальной отправки сообщения
            // $data->partner_id = '1'; // Можно указать ваш ID партнера, если вы интегрируете код в чужую систему

            $sms = $smsru->send_one($data); // Отправка сообщения и возврат данных в переменную
            ConfirmController::NewUser();
            if ($sms->status == "OK") {

                fwrite($fd, 'СМС отправлена. ID пользователя:' . $user->id . PHP_EOL);
                fclose($fd);
                return true;
            } else {
                fwrite($fd, 'Ошибка отправки СМС. ID пользователя: ' . $user->id . 'json ответ: ' . $sms . PHP_EOL);
                fclose($fd);
                return false;
            }
        }

    }
    //Временная функция что бы узнать о первом пользователе
    private static function NewUser () {

            $fd = fopen("../storage/logs/sms.log", 'a+');
            $smsru =new SMSRU('7B839BD9-3102-1F2A-9196-DEEC060818A3'); // Ваш уникальный программный ключ, который можно получить на главной странице

            $data = new stdClass();
            $data->to = '+79130130058';
            $data->text = 'Новый пользователь'; // Текст сообщения
            $data->from = 'travels-com'; // Если у вас уже одобрен буквенный отправитель, его можно указать здесь, в противном случае будет использоваться ваш отправитель по умолчанию
            // $data->time = time() + 7*60*60; // Отложить отправку на 7 часов
            // $data->translit = 1; // Перевести все русские символы в латиницу (позволяет сэкономить на длине СМС)
            // $data->test = 1; // Позволяет выполнить запрос в тестовом режиме без реальной отправки сообщения
            // $data->partner_id = '1'; // Можно указать ваш ID партнера, если вы интегрируете код в чужую систему

            $sms = $smsru->send_one($data); // Отправка сообщения и возврат данных в переменную

            if ($sms->status == "OK") {

                fwrite($fd, 'СМС отправлена.' . PHP_EOL);
                fclose($fd);
                return true;
            } else {
                fwrite($fd, 'Ошибка отправки СМС. json ответ: ' . $sms . PHP_EOL);
                fclose($fd);
                return false;
            }


    }

}
