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
        $model = PetersEye::getActive();
        if (!$model) {
            return null;
        }

        return PetersEyeApiItem::from($model);
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function actionParticipate()
    {
        return PetersEyeService::participate();
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function actionSubmit()
    {
        $coordinates = Yii::$app->request->post('coordinates');
        return PetersEyeService::submit($coordinates);
    }

}
