<?php

namespace app\controllers;

use common\models\Category;

class CategoryController extends BaseApiController
{
    public $modelClass = 'common\models\Category';

    /**
     * @param $parent_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionList($parent_id = null)
    {
        if ($parent_id) {
            return Category::find()->where(['parent_id' => $parent_id])->orderBy('position ASC')->all();
        } else {
            return Category::find()->where(['parent_id' => null])->orderBy('position ASC')->all();
        }
    }

    /**
     * @param $id
     * @return Category|null
     */
    public function actionDetails($id)
    {
        return Category::findOne($id);
    }
}
