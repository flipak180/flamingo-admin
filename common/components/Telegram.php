<?php

namespace common\components;

use voku\helper\HtmlDomParser;
use Yii;
use yii\httpclient\Client;

/**
 *
 */
class Telegram
{
    const int MY_ID = 909486;
    const int IR_ID = 235762190;
    const string GATEWAY_URL = 'https://gatewayapi.telegram.org';
    const string GATEWAY_TOKEN = 'AAGjFAAA8z5FQoIJHPmoUCAsjdz0aS_y72rcyNYVnt04GQ';
    const string BOT_URL = 'https://api.telegram.org/bot';
    const string BOT_TOKEN = '8047430825:AAFOJ3h-GEVayLe5SK-_BK7qcEZHpO3CqW4';

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

        Yii::info($response->data);

        return $response->isOk;
    }

    /**
     * @param $text
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public static function sendNotification($text): bool
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setFormat(Client::FORMAT_JSON)
            ->addHeaders(['Authorization' => 'Bearer ' . self::GATEWAY_TOKEN,])
            ->setUrl(self::BOT_URL . self::BOT_TOKEN . '/sendMessage')
            ->setData(['chat_id' => self::MY_ID, 'text' => $text, 'parse_mode' => 'HTML'])
            ->send();

        Yii::info($response->data);

        return $response->isOk;
    }

    public static function katok()
    {
        include_once(Yii::getAlias('@webroot') . '/simple_html_dom.php');

        // file_put_contents(Yii::getAlias('@webroot') . '/katok.txt', '');

        $days = [];
        $currentDays = file_get_contents(Yii::getAlias('@webroot') . '/katok.txt');
        $html = file_get_html('https://spb.kassir.ru/sport/chempionat-rossii-po-figurnomu-kataniyu-na-konkah-2026');
        //$html = file_get_html('https://spb.kassir.ru/teatr/misha');
        foreach($html->find('.event-date-selector-tab') as $element) {
            $days[] = trim(str_replace(['Купить'], '', $element->plaintext));
        }

        $text = '<a href="https://spb.kassir.ru/sport/chempionat-rossii-po-figurnomu-kataniyu-na-konkah-2026">' . implode("\n", $days) . '</a>';
        //echo $text;
        if ($text == $currentDays) {
            return false;
        }

        file_put_contents(Yii::getAlias('@webroot') . '/katok.txt', $text);
        // Yii::info($text);

        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setFormat(Client::FORMAT_JSON)
            ->addHeaders(['Authorization' => 'Bearer ' . self::GATEWAY_TOKEN])
            ->setUrl(self::BOT_URL . self::BOT_TOKEN . '/sendMessage')
            ->setData(['chat_id' => self::IR_ID, 'text' => $text, 'parse_mode' => 'HTML'])
            ->send();

        // Yii::info($response->data);

        return $response->isOk;
    }

}
