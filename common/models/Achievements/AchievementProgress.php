<?php

namespace common\models\Achievements;

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
            'user_id' => 'User ID',
            'achievement_id' => 'Achievement ID',
            'points' => 'Points',
            'unlocked_at' => 'Unlocked At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
