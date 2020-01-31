<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;
use Validator;

class BulkSmsController extends Controller
{
    //
    public function sendSms(Request $request)
    {
        // $url = 'https://smsc.ru/sys/send.php?login=vk_569802&psw=Givemethemoney1&phones=+79874991388&mes=Тест';
        // $ch = curl_init($url);
        // $contents = curl_exec($ch);
        // $response = json_decode($contents, true);
        // return $response;
    }
}
