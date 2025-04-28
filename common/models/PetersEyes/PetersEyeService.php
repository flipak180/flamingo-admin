<?php

namespace common\models\PetersEyes;

use common\components\Helper;
use Yii;

class PetersEyeService
{

    const STATUS_NOT_PARTICIPATE = 1;
    const STATUS_PARTICIPATE = 2;
    const STATUS_WINNER = 3;
    const STATUS_NOT_WINNER = 4;

    /**
     * @return string|null
     * @throws \yii\db\Exception
     */
    public static function participate()
    {
        /** @var PetersEye $model */
        $model = PetersEye::getActive();
        if (!$model) {
            return null;
        }

        $existingModel = PetersEyeUser::find()->where([
            'peters_eye_id' => $model->id,
            'user_id' => Yii::$app->user->id,
        ])->one();
        if ($existingModel) {
            return null;
        }

        $user = new PetersEyeUser();
        $user->peters_eye_id = $model->id;
        $user->user_id = Yii::$app->user->id;
        return $user->save() ? $model->image->path : null;
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

    /**
     * @return string[]
     */
    public static function getStatusesList()
    {
        return [
            self::STATUS_NOT_PARTICIPATE => 'Вы не участвуете',
            self::STATUS_PARTICIPATE => 'Вы участвуете',
            self::STATUS_WINNER => 'Вы победитель',
            self::STATUS_NOT_WINNER => 'Повезет в другой раз',
        ];
    }

    /**
     * @return int|void
     */
    public static function getUserStatus()
    {
        /** @var PetersEye $model */
        $model = PetersEye::getActive();
        if (!$model) {
            return self::STATUS_NOT_PARTICIPATE;
        }

        $isParticipate = PetersEyeUser::find()
            ->where([
                'peters_eye_id' => $model->id,
                'user_id' => Yii::$app->user->id
            ])
            ->exists();
        if (!$isParticipate) {
            return self::STATUS_NOT_PARTICIPATE;
        }

        if ($model->winner_id && $model->winner_id == Yii::$app->user->id) {
            return self::STATUS_WINNER;
        }
        if ($model->winner_id && $model->winner_id != Yii::$app->user->id) {
            return self::STATUS_NOT_WINNER;
        }
    }

}
