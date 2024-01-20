<?php

namespace app\controllers;

use common\models\Quest;
use common\models\QuestReadModel;

class QuestsController extends BaseApiController
{

    /**
     * @return array
     * @throws \himiklab\thumbnail\FileNotFoundException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function actionList()
    {
        $result = [];
        /** @var Quest[] $quests */
        $quests = Quest::find()->orderBy('id DESC')->all();
        foreach ($quests as $quest) {
            $result[] = QuestReadModel::from($quest)->getShortInfo();
        }

        return $result;
    }

    /**
     * @param $id
     * @return array
     * @throws \himiklab\thumbnail\FileNotFoundException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function actionDetails($id)
    {
        /** @var Quest $quest */
        $quest = Quest::findOne($id);

        return QuestReadModel::from($quest)->getDetails();
    }

}
