<?php

namespace common\components;

use yii\httpclient\Client;

/**
 *
 */
class Telegram
{
    const string GATEWAY_URL = 'https://gatewayapi.telegram.org';
    const string GATEWAY_TOKEN = 'AAGjFAAA8z5FQoIJHPmoUCAsjdz0aS_y72rcyNYVnt04GQ';

    /**
     * @param $phone
     * @param $code
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public static function sendConfirmationCode($phone, $code): bool
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setFormat(Client::FORMAT_JSON)
            ->addHeaders(['Authorization' => 'Bearer ' . self::GATEWAY_TOKEN,])
            ->setUrl(self::GATEWAY_URL . '/sendVerificationMessage')
            ->setData(['phone_number' => Helper::clearPhone($phone), 'code' => $code])
            ->send();

        \Yii::info($response->data);

        return $response->isOk;
    }

}
