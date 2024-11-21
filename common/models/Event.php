<?php

namespace common\models;

use common\behaviors\ImageBehavior;
use common\models\Places\Place;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "events".
 *
 * @property int $event_id
 * @property string $title
 * @property string|null $subtitle
 * @property int|null $place_id
 * @property string|null $description
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Place $place
 * @property ImageModel[] $images
 */
class Event extends \yii\db\ActiveRecord
{
    public $images_field;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'events';
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
            [['place_id'], 'default', 'value' => null],
            [['place_id'], 'integer'],
            [['description'], 'string'],
            [['title', 'subtitle'], 'string', 'max' => 255],
            [['images_field'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'event_id' => 'ID',
            'title' => 'Название',
            'subtitle' => 'Подзаголовок',
            'images_field' => 'Изображения',
            'place_id' => 'Место',
            'description' => 'Описание',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlace()
    {
        return $this->hasOne(Place::className(), ['place_id' => 'place_id']);
    }
}
