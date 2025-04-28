<?php

namespace api\controllers;

use api\models\PetersEyes\PetersEyeApiItem;
use common\models\PetersEyes\PetersEye;
use common\models\PetersEyes\PetersEyeService;
use Yii;
use yii\db\Exception;
use yii\filters\auth\HttpBearerAuth;

class PetersEyesController extends BaseApiController
{
    public $modelClass = 'common\models\PetersEyes\PetersEye';

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
        $model = PetersEye::getCurrent();
        if (!$model) {
            return null;
        }

        return PetersEyeApiItem::from($model);
    }

    /**
     * @return array|null
     * @throws Exception
     */
    public function actionParticipate()
    {
        /** @var PetersEye $model */
        $model = PetersEye::getCurrent();
        if (!$model) {
            return null;
        }

        (new PetersEyeService($model))->participate();
        return PetersEyeApiItem::from($model);
    }

    /**
     * @return array|null
     * @throws Exception
     */
    public function actionSubmit()
    {
        /** @var PetersEye $model */
        $model = PetersEye::getCurrent();
        if (!$model) {
            return null;
        }

        $coordinates = Yii::$app->request->post('coordinates');
        (new PetersEyeService($model))->submit($coordinates);
        return PetersEyeApiItem::from($model);
    }

}
