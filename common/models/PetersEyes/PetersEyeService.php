<?php

namespace common\models\PetersEyes;

use common\components\Helper;
use Yii;

class PetersEyeService
{

    /**
     * @return bool
     * @throws \yii\db\Exception
     */
    public static function participate()
    {
        /** @var PetersEye $model */
        $model = PetersEye::getActive();
        if (!$model) {
            return false;
        }

        $existingModel = PetersEyeUser::find()->where([
            'peters_eye_id' => $model->id,
            'user_id' => Yii::$app->user->id,
        ])->one();
        if ($existingModel) {
            return false;
        }

        $user = new PetersEyeUser();
        $user->peters_eye_id = $model->id;
        $user->user_id = Yii::$app->user->id;
        return $user->save();
    }

    /**
     * @param $coordinates
     * @return bool
     * @throws \yii\db\Exception
     */
    public static function submit($coordinates)
    {
        /** @var PetersEye $model */
        $model = PetersEye::getActive();
        if (!$model) {
            return false;
        }

        $distance = Helper::getDistance($coordinates, $model->coords);
        Yii::info($distance);
        if ($distance > $model->radius) {
            return false;
        }

        $model->winner_id = Yii::$app->user->id;
        $model->win_at = date('Y-m-d H:i:s');
        return $model->save();
    }

}
