<?php

namespace api\controllers;

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
            'except' => ['get-active'],
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

}
