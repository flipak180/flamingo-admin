<?php

namespace common\models\PushTokens;

/**
 *
 */
class PushTokensSearch extends PushToken
{
    /**
     * @return array
     */
    public static function getAllDeviceTokens()
    {
        return PushToken::find()
            ->select('token')
            ->column();
    }

    /**
     * @return array
     */
    public static function getTestTokens()
    {
        return PushToken::find()
            ->select('token')
            ->where(['user_id' => 1])
            ->column();
    }
}
