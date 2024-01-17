<?php

namespace common\models;

use common\behaviors\ImageBehavior;
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
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Quest $quest
 */
class QuestPlace extends \yii\db\ActiveRecord
{
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
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'quest_id' => 'Quest ID',
            'title' => 'Title',
            'description' => 'Description',
            'location' => 'Location',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
