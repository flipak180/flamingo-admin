<?php

namespace common\models\Achievements;

use common\base\DataTransferObject;
use yii\helpers\ArrayHelper;

/**
 *
 */
class AchievementApiItem extends DataTransferObject
{
    public $id;
    public $title;
    public $description;
    public $points;
    public $progress;

    /**
     * @param $model
     * @return $this
     * @throws \Exception
     */
    public function from($model): AchievementApiItem
    {
        $this->id = $model->id;
        $this->title = $model->title;
        $this->description = $model->description;
        $this->points = $model->points;
        $this->progress = $this->getProgress();
        return $this;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    private function getProgress()
    {
        $achievementsProgressMap = $this->getFromExtra('achievementsProgressMap', []);
        return ArrayHelper::getValue($achievementsProgressMap, $this->id, [
            'points' => 0,
            'unlocked_at' => null
        ]);
    }
}
