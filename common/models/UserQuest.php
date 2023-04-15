<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "user_quests".
 *
 * @property int $user_quest_id
 * @property int $user_id
 * @property int $quest_id
 * @property int $stage
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $user
 * @property Category $quest
 */
class UserQuest extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_quests';
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
            [['user_id', 'quest_id'], 'required'],
            [['user_id', 'quest_id', 'stage'], 'default', 'value' => null],
            [['user_id', 'quest_id', 'stage'], 'integer'],
            ['quest_id', 'unique', 'targetAttribute' => ['user_id', 'quest_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_quest_id' => 'ID',
            'user_id' => 'Пользователь',
            'quest_id' => 'Квест',
            'stage' => 'Уровень',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuest()
    {
        return $this->hasOne(Category::className(), ['category_id' => 'quest_id']);
    }
}
