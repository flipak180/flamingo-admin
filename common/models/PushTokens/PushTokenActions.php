<?php

namespace common\models\PushTokens;

use Yii;

/**
 * This is the model class for table "push_tokens".
 *
 * @property int $id
 * @property string $token
 * @property int|null $user_id
 * @property string $created_at
 * @property string $updated_at
 */
class PushTokenActions extends PushToken
{

    /**
     * @param $token
     * @return bool
     * @throws \yii\db\Exception
     */
    public static function register($token)
    {
        $userId = Yii::$app->user->id;
        $pushToken = PushToken::findOne(['token' => $token]);
        if (!$pushToken) {
            $pushToken = new PushToken();
            $pushToken->token = $token;
        }
        $pushToken->user_id = $userId;
        return $pushToken->save();
    }

    /**
     * @param $token
     * @return bool
     * @throws \yii\db\Exception
     */
    public static function detach($token)
    {
        $pushToken = PushToken::findOne(['token' => $token]);
        if (!$pushToken) {
            return false;
        }
        $pushToken->user_id = null;
        return $pushToken->save();
    }
}
