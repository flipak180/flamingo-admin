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
}
