<?php

namespace app\controllers;

use common\models\Quest;
use common\models\QuestPlace;
use common\models\QuestReadModel;
use himiklab\thumbnail\EasyThumbnailImage;
use Yii;
use yii\filters\auth\HttpBearerAuth;

class QuestsController extends BaseApiController
{

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'only' => ['start'],
        ];
        return $behaviors;
    }

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

    /**
     * @param $quest_id
     * @param $place_id
     * @return array
     * @throws \himiklab\thumbnail\FileNotFoundException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function actionPlace($quest_id, $place_id)
    {
        /** @var QuestPlace $questPlace */
        $questPlace = QuestPlace::findOne([
            'id' => $place_id,
            'quest_id' => $quest_id,
        ]);

        $images = [];
        foreach ($questPlace->images as $image) {
            $images[] = EasyThumbnailImage::thumbnailFileUrl(Yii::getAlias('@frontend_web').$image->path, 716, 600, EasyThumbnailImage::THUMBNAIL_OUTBOUND, 100);
        }

        return [
            'id' => $questPlace->id,
            'title' => $questPlace->title,
            'image' => count($images) ? $images[0] : '',
            'images' => $images,
            'description' => $questPlace->description,
            'coords' => $questPlace->coords,
        ];
    }

    /**
     * @return void
     */
    public function actionStart()
    {
        return 'ok';
    }

}
