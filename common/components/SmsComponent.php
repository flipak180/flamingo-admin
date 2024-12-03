<?php
namespace common\components;


use yii\base\Component;

class SmsComponent extends Component {

    const LOGIN = 'flipak180@mail.ru';
    const API_KEY = 'VFwhBU1mPzjx5sFy6RmlNNvwyzZtvbvp';

    public function sendSMS($phone, $text) {
        $phone = Helper::clearPhone($phone);

        $ch = curl_init('https://'.self::LOGIN.':'.self::API_KEY.'@gate.smsaero.ru/v2/sms/send?number='.$phone.'&text='.$text.'&sign=SMS Aero');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $response = curl_exec($ch);
        curl_close($ch);
    }

}
