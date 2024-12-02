<?php

namespace app\controllers;

use common\models\Categories\Category;
use common\models\Places\Place;
use common\models\User;
use common\models\UserQuest;
use common\models\Visit;
use Yii;
use yii\base\ErrorException;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class QuestController extends Controller
{
    /**
     * @return bool
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionStart()
    {
        $params = Yii::$app->request->getBodyParams();
        if (!isset($params['quest_id']) || !isset($params['phone'])) {
            throw new BadRequestHttpException('Некорректные данные');
        }

        $user = User::findOne(['phone' => $params['phone']]);
        if (!$user) {
            throw new NotFoundHttpException('Пользователь не найден');
        }

        $quest = Category::findOne(['category_id' => $params['quest_id'], 'type' => Category::TYPE_QUEST]);
        if (!$quest) {
            throw new NotFoundHttpException('Квест не найден');
        }

        $userQuest = new UserQuest();
        $userQuest->user_id = $user->user_id;
        $userQuest->quest_id = $quest->category_id;
        $userQuest->stage = 1;

        if (!$userQuest->validate()) {
            throw new BadRequestHttpException('Вы уже начали квест');
        }

        return $userQuest->save();
    }

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

        $place = Place::findOne($params['place_id']);
        if (!$place || !$place->category_id) {
            throw new NotFoundHttpException('Место не найдено');
        }

        $visit = new Visit();
        $visit->place_id = $place->place_id;
        $visit->user_id = $user->user_id;
        if (!$visit->validate()) {
            throw new BadRequestHttpException('Вы уже отметились');
        }

        $userQuest = UserQuest::findOne(['user_id' => $user->user_id, 'quest_id' => $place->category_id]);
        if (!$userQuest) {
            throw new NotFoundHttpException('Квест не найден');
        }

        $userQuest->stage += 1;

        $transaction = Yii::$app->db->beginTransaction();
        if (!$visit->save()) {
            $transaction->rollback();
            throw new ErrorException('Model cannot be saved.');
        }
        if (!$userQuest->save()) {
            $transaction->rollback();
            throw new ErrorException('Anothermodel cannot be saved.');
        }
        $transaction->commit();

        return true;
    }
}
