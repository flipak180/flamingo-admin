<?php

namespace app\controllers;

use common\models\Category;
use common\models\Place;
use common\models\User;
use common\models\Visit;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class PlacesController extends BaseApiController
{

    /**
     * @param $category_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionList($category_id = null)
    {
        if (!$category_id) {
            return [];
        }

        $category = Category::findOne($category_id);
        if (!$category) {
            throw new NotFoundHttpException('Категория не найдена');
        }

        $tagIds = [];
        foreach ($category->categoryTags as $categoryTag) {
            $tagIds[] = $categoryTag->tag_id;
        }

        $orderDir = ($category->type == Category::TYPE_CATALOG) ? 'DESC' : 'ASC';

        return Place::find()->joinWith('placeTags')
            ->where(['category_id' => $category_id])
            ->orWhere(['in', 'tag_id', $tagIds])
            ->orderBy('place_id ' . $orderDir)
            ->all();
    }

    /**
     * @param $id
     * @return Place|null
     */
    public function actionView($id)
    {
        return Place::findOne($id);
    }

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

        $place = Place::findOne($params['place_id']);
        if (!$place) {
            throw new NotFoundHttpException('Место не найдено');
        }

        $visit = new Visit();
        $visit->place_id = $place->place_id;
        $visit->user_id = $user->user_id;
        if (!$visit->validate()) {
            throw new BadRequestHttpException('Вы уже отметились');
        }

        return $visit->save();
    }
}
