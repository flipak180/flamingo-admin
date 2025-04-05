<?php
namespace common\components;


use Yii;
use yii\base\Component;
use yii\httpclient\Client;

class SmsComponentNew extends Component
{
    const LOGIN = 'baltzdrav';
    const API_KEY = 'cDOjEEbrRmZNBLGgwCVDxOgV';
    const SENDER_NAME = 'Flamingo';

    public function send($phone, $text = '')
    {
        $phone = Helper::clearPhone($phone);

        $ts = 'ts-value-' . time();
        $secret = md5($ts . self::API_KEY);

        $client = new Client();
        $response = $client->createRequest()
            ->setUrl('https://cp.redsms.ru/api/message')
            ->setMethod('POST')
            ->setFormat(Client::FORMAT_JSON)
            ->addHeaders([
                'login: ' . self::LOGIN,
                'ts: ' . $ts,
                'secret: ' . $secret,
                'Content-type: application/json'
            ])
            ->setData([
                'route' => 'tgauth',
                //'route' => 'pushok',
                //'route' => 'sms',
                //'from' => self::SENDER_NAME,
                'to' => $phone,
                //'text' => 'phone'
                'text' => $text
            ])
            ->send();

        return $response->data['success'];
    }

    public function status($uuid)
    {
        $ts = 'ts-value-' . time();
        $secret = md5($ts . self::API_KEY);

        $client = new Client();
        $response = $client->createRequest()
            ->setUrl('https://cp.redsms.ru/api/message/' . $uuid)
            ->setFormat(Client::FORMAT_JSON)
            ->addHeaders([
                'login: ' . self::LOGIN,
                'ts: ' . $ts,
                'secret: ' . $secret,
                'Content-type: application/json'
            ])
            ->send();


        Yii::info($response->data);

        return $response->data;
    }

}
