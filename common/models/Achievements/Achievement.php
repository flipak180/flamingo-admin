<?php

namespace common\models\Achievements;

use common\behaviors\ImageBehavior;
use common\models\ImageModel;
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
 * @property AchievementCategory $category
 * @property ImageModel $image
 */
class Achievement extends \yii\db\ActiveRecord
{
    public $image_field;

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
            [
                'class' => ImageBehavior::class,
                'attribute' => 'image_field',
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
            [['image_field'], 'file', 'extensions' => ['png', 'jpg', 'jpeg', 'webp'], 'maxSize' => 1024*1024*10],
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
            'image_field' => 'Изображение',
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
    public function getCategory()
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
