<?php

namespace common\models;

use common\behaviors\ImageBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "quests".
 *
 * @property int $id
 * @property string $title
 * @property string|null $subtitle
 * @property string|null $description
 * @property int|null $distance
 * @property int|null $time
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ImageModel[] $images
 * @property ImageModel $image
 * @property QuestPlace[] $questPlaces
 */
class Quest extends \yii\db\ActiveRecord
{
    public $images_field;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'quests';
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
            [
                'class' => ImageBehavior::class,
                'attribute' => 'images_field',
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
            [['description'], 'string'],
            [['distance', 'time'], 'integer'],
            [['title', 'subtitle'], 'string', 'max' => 255],
            [['images_field'], 'safe']
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
            'subtitle' => 'Подзаголовок',
            'description' => 'Описание',
            'distance' => 'Дистанция (в метрах)',
            'time' => 'Длительность (в минутах)',
            'images_field' => 'Изображения',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestPlaces()
    {
        return $this->hasMany(QuestPlace::className(), ['quest_id' => 'id'])->orderBy('id ASC');
    }
}
