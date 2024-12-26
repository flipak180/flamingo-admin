<?php

namespace common\models\Achievements;

use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "achievement_categories".
 *
 * @property int $id
 * @property string $title
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Achievement[] $achievements
 */
class AchievementCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'achievement_categories';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAchievements()
    {
        return $this->hasMany(Achievement::className(), ['category_id' => 'id']);
    }

    /**
     * @return array
     */
    public static function getList()
    {
        $result = [];
        /** @var AchievementCategory[] $models */
        $models = AchievementCategory::find()->all();
        foreach ($models as $model) {
            $result[] = [
                'id' => $model->id,
                'title' => $model->title,
            ];
        }
        return $result;
    }
}
