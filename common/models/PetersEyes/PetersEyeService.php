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
        $currentUser = $this->getCurrentUser();
        if ($currentUser) {
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
        $currentUser = $this->getCurrentUser();

        $distance = Helper::getDistance($coordinates, $this->model->coords);
        if ($distance > $this->model->radius || $this->model->winner_id) {
            return false;
        }

        $this->model->winner_id = Yii::$app->user->id;
        $this->model->win_at = date('Y-m-d H:i:s');
        $this->model->save();

        $currentUser->is_winner = true;
        $currentUser->save();

        return true;
    }

    /**
     * @return int|void
     */
    public function getUserStatus()
    {
        /** @var PetersEyeUser $petersEyeUser */
        $petersEyeUser = PetersEyeUser::find()->where([
            'peters_eye_id' => $this->model->id,
            'user_id' => Yii::$app->user->id
        ])->one();

        if (!$petersEyeUser) {
            return self::STATUS_NOT_PARTICIPATE;
        }
        if (!$this->model->winner_id) {
            return self::STATUS_PARTICIPATE;
        }
        if ($this->model->winner_id && $petersEyeUser->is_winner) {
            return self::STATUS_WINNER;
        }
        if ($this->model->winner_id && !$petersEyeUser->is_winner) {
            return self::STATUS_NOT_WINNER;
        }
    }

    /**
     * @return PetersEyeUser
     */
    private function getCurrentUser()
    {
        /** @var PetersEyeUser $model */
        $model = PetersEyeUser::find()->where([
            'peters_eye_id' => $this->model->id,
            'user_id' => Yii::$app->user->id,
        ])->one();
        return $model;
    }

}
