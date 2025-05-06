<?php

namespace api\controllers;

use api\models\PetersEyeApiService;
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
            'optional' => ['get-active'],
        ];
        return $behaviors;
    }

    /**
     * @return array|null
     */
    public function actionGetActive()
    {
        return (new PetersEyeApiService())->get();
    }

    /**
     * @return array|null
     * @throws Exception
     */
    public function actionParticipate()
    {
        return (new PetersEyeApiService())->participate();
    }

    /**
     * @return array|null
     * @throws Exception
     */
    public function actionSubmit()
    {
        $coordinates = Yii::$app->request->post('coordinates');
        return (new PetersEyeApiService())->submit($coordinates);
    }

    /**
     * @return null
     * @throws Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionReset()
    {
        return (new PetersEyeApiService())->reset();
    }

}
