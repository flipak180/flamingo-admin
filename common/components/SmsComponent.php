<?php
namespace common\components;


use yii\base\Component;
use yii\httpclient\Client;

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

        $client = new Client();
        $response = $client->createRequest()
            ->setUrl('https://cp.redsms.ru/api/message')
            ->setMethod('POST')
            ->setFormat(Client::FORMAT_JSON)
            ->setHeaders([
                'login: ' . self::LOGIN,
                'ts: ' . $ts,
                'secret: ' . $secret,
                'Content-type: application/json'
            ])
            ->setData([
                //'route' => 'tgauth',
                //'route' => 'pushok',
                'route' => 'fcall,voice,tgauth,vk,sms',
                //'route' => 'sms',
                //'from' => self::SENDER_NAME,
                'to' => $phone,
                //'text' => 'phone'
                'text' => $text,
                'voice_text' => 'Ваш код авторизации - ' . implode(' ', str_split($text)) . '. Повторяю - ' . implode(' ', str_split($text)),
                'sms_text' => 'Ваш код авторизации ' . $text,
                'vk_text' => 'Ваш код авторизации ' . $text,
            ])
            ->send();

        \Yii::info($response->data);

        return $response->isOk ? $response->data['success'] : false;

//        $ch = curl_init();
//
//        curl_setopt($ch, CURLOPT_URL, 'https://cp.redsms.ru/api/message');
//        curl_setopt($ch, CURLOPT_POST, 1);
//        curl_setopt($ch, CURLOPT_HTTPHEADER, [
//            'login: ' . self::LOGIN,
//            'ts: ' . $ts,
//            'secret: ' . $secret,
//            'Content-type: application/json'
//        ]);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
//            //'route' => 'tgauth',
//            //'route' => 'pushok',
//            'route' => 'fcall',
//            //'route' => 'sms',
//            //'from' => self::SENDER_NAME,
//            'to' => $phone,
//            //'text' => 'phone'
//            'text' => $text
//        ]));
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//
//        $response = curl_exec($ch);
//        $info = curl_getinfo($ch);
//
//        curl_close($ch);
//
//        $response_data = json_decode($response, true);
//        \Yii::info($response_data);
//
//        return $response_data['success'];
    }

}
