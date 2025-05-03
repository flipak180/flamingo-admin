<?php

namespace api\models;

use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\Output\QRMarkupSVG;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use common\components\Helper;
use common\models\PetersEyes\PetersEye;
use common\models\PetersEyes\PetersEyeUser;
use Yii;

class PetersEyeApiService
{
    private PetersEye $model;
    private PetersEyeUser|null $user;

    const STATUS_NOT_PARTICIPATE = 1;
    const STATUS_PARTICIPATE = 2;
    const STATUS_WINNER = 3;
    const STATUS_NOT_WINNER = 4;

    public function __construct()
    {
        $this->model = PetersEye::getCurrent();
        $this->user = $this->getCurrentUser();
    }

    /**
     * @return array|null
     * @throws \yii\db\Exception
     */
    public function participate()
    {
        if ($this->user) {
            return $this->getResponse();
        }

        $user = new PetersEyeUser();
        $user->peters_eye_id = $this->model->id;
        $user->user_id = Yii::$app->user->id;
        $user->save();

        return $this->getResponse();
    }

    /**
     * @param $coordinates
     * @return array
     * @throws \yii\db\Exception
     */
    public function submit($coordinates)
    {
        $distance = Helper::getDistance($coordinates, $this->model->coords);
//        if ($distance > $this->model->radius || $this->model->winner_id) {
//            return false;
//        }

        $this->model->winner_id = Yii::$app->user->id;
        $this->model->win_at = date('Y-m-d H:i:s');
        $this->model->save();

        $this->user->is_winner = true;
        $this->user->save();

        return $this->getResponse();
    }

    /**
     * @return int|void
     */
    public function getUserStatus()
    {
        if (!$this->user) {
            return self::STATUS_NOT_PARTICIPATE;
        }
        if (!$this->model->winner_id) {
            return self::STATUS_PARTICIPATE;
        }
        if ($this->model->winner_id && $this->user->is_winner) {
            return self::STATUS_WINNER;
        }
        if ($this->model->winner_id && !$this->user->is_winner) {
            return self::STATUS_NOT_WINNER;
        }
    }

    /**
     * @return array
     * @throws \Throwable
     * @throws \yii\db\Exception
     * @throws \yii\db\StaleObjectException
     */
    public function reset()
    {
        if ($this->user) {
            $this->user->delete();
        }

        $this->model->winner_id = null;
        $this->model->win_at = null;
        $this->model->save();

        return $this->getResponse();
    }

    /**
     * @return array
     */
    public function get()
    {
        return $this->getResponse();
    }

    /**
     * @return PetersEyeUser|null
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

    /**
     * @return array
     */
    private function getResponse()
    {
        return [
            'id' => $this->model->id,
            'prize' => $this->model->prize,
            'status' => $this->getUserStatus(),
            'image' => $this->model->image->path,
            'qr_code' => $this->user?->qr_code ? (new QRCode)->render($this->user->qr_code) : null,
            'total_users' => PetersEyeUser::find()->where(['peters_eye_id' => $this->model->id])->count(),
        ];
    }

}
