<?php

namespace api\controllers;

use common\components\Helper;
use common\models\PetersEye;
use common\models\PetersEyeUser;
use Yii;
use yii\filters\auth\HttpBearerAuth;

class PetersEyesController extends BaseApiController
{
    public $modelClass = 'common\models\PetersEye';

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];
        return $behaviors;
    }

    /**
     * @return array|null
     */
    public function actionGetActive()
    {
        /** @var PetersEye $model */
        $model = PetersEye::getActive();
        if (!$model) {
            return null;
        }

        return [
            'id' => $model->id,
            'prize' => $model->prize,
            'image' => Yii::$app->user->id ? $model->image->path : null,
        ];
    }

    /**
     * @return bool
     * @throws \yii\db\Exception
     */
    public function actionParticipate()
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

    public function actionSubmit()
    {
        $coordinates = Yii::$app->request->post('coordinates');

        /** @var PetersEye $model */
        $model = PetersEye::getActive();
        if (!$model) {
            return false;
        }

        $distance = Helper::getDistance($coordinates, $model->coords);
        if ($distance > 500) {
            return false;
        }

        $model->winner_id = Yii::$app->user->id;
        $model->win_at = date('Y-m-d H:i:s');
        return $model->save();
    }

}
