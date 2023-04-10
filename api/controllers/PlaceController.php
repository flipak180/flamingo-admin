<?php

namespace app\controllers;

use common\models\User;
use common\models\Visit;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class PlaceController extends BaseApiController
{
    public $modelClass = 'common\models\Place';

    /**
     * @return bool
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionVisit()
    {
        $params = Yii::$app->request->getBodyParams();
        if (!isset($params['place_id']) || !isset($params['phone'])) {
            throw new BadRequestHttpException('Некорректные данные');
        }

        $user = User::findOne(['phone' => $params['phone']]);
        if (!$user) {
            throw new NotFoundHttpException('Пользователь не найден');
        }

        $visit = new Visit();
        $visit->place_id = $params['place_id'];
        $visit->user_id = $user->user_id;
        if (!$visit->validate()) {
            throw new BadRequestHttpException('Вы уже отметились');
        }

        return $visit->save();
    }
}
