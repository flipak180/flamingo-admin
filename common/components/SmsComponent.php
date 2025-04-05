<?php
namespace common\components;


use yii\base\Component;

class SmsComponent extends Component
{

    const LOGIN = 'baltzdrav';
    const API_KEY = 'cDOjEEbrRmZNBLGgwCVDxOgV';
    const SENDER_NAME = 'Flamingo';

    public function send($phone, $text)
    {
        $phone = Helper::clearPhone($phone);

        $ts = 'ts-value-' . time();
        $secret = md5($ts . self::API_KEY);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://cp.redsms.ru/api/message');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'login: ' . self::LOGIN,
            'ts: ' . $ts,
            'secret: ' . $secret,
            'Content-type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'route' => 'tgauth',
            //'route' => 'pushok',
            //'route' => 'sms',
            //'from' => self::SENDER_NAME,
            'to' => $phone,
            //'text' => 'phone'
            'text' => $text
        ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $info = curl_getinfo($ch);

        curl_close($ch);

        $response_data = json_decode($response, true);
        \Yii::info($response_data);

        return $response_data['success'];
    }

}
