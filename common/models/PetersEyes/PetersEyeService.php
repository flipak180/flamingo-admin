<?php

namespace common\models\PetersEyes;

use common\components\Helper;
use Yii;

class PetersEyeService
{
    private PetersEye $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    const STATUS_NOT_PARTICIPATE = 1;
    const STATUS_PARTICIPATE = 2;
    const STATUS_WINNER = 3;
    const STATUS_NOT_WINNER = 4;

    /**
     * @return string|null
     * @throws \yii\db\Exception
     */
    public function participate()
    {
        $existingModel = PetersEyeUser::find()->where([
            'peters_eye_id' => $this->model->id,
            'user_id' => Yii::$app->user->id,
        ])->one();
        if ($existingModel) {
            return null;
        }

        $user = new PetersEyeUser();
        $user->peters_eye_id = $this->model->id;
        $user->user_id = Yii::$app->user->id;
        return $user->save() ? $this->model->image->path : null;
    }

    /**
     * @param $coordinates
     * @return bool
     * @throws \yii\db\Exception
     */
    public function submit($coordinates)
    {
        $distance = Helper::getDistance($coordinates, $this->model->coords);
        if ($distance > $this->model->radius) {
            return false;
        }

        $this->model->winner_id = Yii::$app->user->id;
        $this->model->win_at = date('Y-m-d H:i:s');
        return $this->model->save();
    }

    /**
     * @return int|void
     */
    public function getUserStatus()
    {
        if (!$this->model) {
            return self::STATUS_NOT_PARTICIPATE;
        }

        $isParticipate = PetersEyeUser::find()
            ->where([
                'peters_eye_id' => $this->model->id,
                'user_id' => Yii::$app->user->id
            ])
            ->exists();
        if (!$isParticipate) {
            return self::STATUS_NOT_PARTICIPATE;
        }

        if ($this->model->winner_id && $this->model->winner_id == Yii::$app->user->id) {
            return self::STATUS_WINNER;
        }
        if ($this->model->winner_id && $this->model->winner_id != Yii::$app->user->id) {
            return self::STATUS_NOT_WINNER;
        }
    }

}
