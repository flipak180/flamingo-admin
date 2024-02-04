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
 * @property int|null $quiz_type
 * @property string|null $quiz_question
 * @property string|null $quiz_answer
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

    const QUIZ_TYPE_OPTIONS = 1;
    const QUIZ_TYPE_LOCATION_SEARCH = 2;

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
            [['quest_id', 'quiz_type'], 'integer'],
            [['description', 'location', 'quiz_question'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['location_field', 'coords_field', 'images_field', 'quiz_answer'], 'safe'],
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
            'quiz_type' => 'Тип загадки',
            'quiz_question' => 'Текст загадки',
            'quiz_answer' => 'Ответ к загадке',
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

    /**
     * @return string[]
     */
    public static function getQuizTypes()
    {
        return [
            self::QUIZ_TYPE_OPTIONS => 'Варианты ответа',
            self::QUIZ_TYPE_LOCATION_SEARCH => 'Поиск места',
        ];
    }
}
