<?php

namespace common\models\Achievements;

use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "achievements".
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property int|null $category_id
 * @property int|null $level
 * @property int|null $points
 * @property int|null $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property AchievementCategory $achievementCategory
 */
class Achievement extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'achievements';
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
            [['category_id', 'level', 'points', 'status'], 'default', 'value' => null],
            [['category_id', 'level', 'points', 'status'], 'integer'],
            [['title', 'description'], 'string', 'max' => 255],
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
            'description' => 'Описание',
            'category_id' => 'Категория',
            'level' => 'Уровень',
            'points' => 'Очки',
            'status' => 'Статус',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAchievementCategory()
    {
        return $this->hasOne(AchievementCategory::className(), ['id' => 'category_id']);
    }

    /**
     * @return string[]
     */
    public static function getStatusesList()
    {
        return [
            self::STATUS_ACTIVE => 'Активна',
            self::STATUS_INACTIVE => 'Неактивна',
        ];
    }

    /**
     * @return string
     */
    public function getStatusName()
    {
        return self::getStatusesList()[$this->status];
    }
}
