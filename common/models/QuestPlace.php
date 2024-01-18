<?php

namespace common\models;

use common\behaviors\ImageBehavior;
use nanson\postgis\behaviors\GeometryBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "quest_places".
 *
 * @property int $id
 * @property int $quest_id
 * @property string $title
 * @property string|null $description
 * @property string|null $location
 * @property string|null $coords
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ImageModel[] $images
 * @property Quest $quest
 */
class QuestPlace extends \yii\db\ActiveRecord
{
    public $location_field;
    public $coords_field;
    public $images_field;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'quest_places';
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
                'class' => GeometryBehavior::class,
                'type' => GeometryBehavior::GEOMETRY_POLYGON,
                'attribute' => 'location',
            ],
            [
                'class' => GeometryBehavior::class,
                'type' => GeometryBehavior::GEOMETRY_POINT,
                'attribute' => 'coords',
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
            [['quest_id', 'title'], 'required'],
            [['quest_id'], 'default', 'value' => null],
            [['quest_id'], 'integer'],
            [['description', 'location'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['location_field', 'coords_field', 'images_field'], 'safe'],
        ];
    }

    /**
     * @return bool
     */
    public function beforeValidate()
    {
        if ($this->location_field) {
            $this->location = json_decode($this->location_field, true);
        }
        if ($this->coords_field) {
            $this->coords = array_map('trim', explode(',', $this->coords_field));
        }
        return parent::beforeValidate();
    }

    /**
     * @return void
     */
    public function afterFind()
    {
        parent::afterFind();

        if ($this->location) {
            $this->location_field = json_encode($this->location);
        }
        if ($this->coords) {
            $this->coords_field = implode(', ', $this->coords);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'quest_id' => 'Квест',
            'title' => 'Название',
            'description' => 'Описание',
            'location' => 'Местоположение',
            'location_field' => 'Местоположение',
            'coords' => 'Координаты',
            'coords_field' => 'Координаты',
            'images_field' => 'Изображения',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuest()
    {
        return $this->hasOne(Quest::className(), ['id' => 'quest_id']);
    }
}
