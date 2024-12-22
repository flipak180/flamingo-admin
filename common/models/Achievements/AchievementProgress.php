<?php

namespace common\models\Achievements;

use common\models\User;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "achievement_progress".
 *
 * @property int $id
 * @property int $user_id
 * @property int $achievement_id
 * @property int|null $points
 * @property string|null $unlocked_at
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $user
 * @property Achievement $achievement
 */
class AchievementProgress extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'achievement_progress';
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
            [['user_id', 'achievement_id'], 'required'],
            [['user_id', 'achievement_id', 'points'], 'default', 'value' => null],
            [['user_id', 'achievement_id', 'points'], 'integer'],
            [['unlocked_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'achievement_id' => 'Достижения',
            'points' => 'Очки',
            'unlocked_at' => 'Дата получения',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAchievement()
    {
        return $this->hasOne(Achievement::className(), ['id' => 'achievement_id']);
    }
}
