<?php

namespace common\models;

use common\behaviors\ImageBehavior;
use nanson\postgis\behaviors\GeometryBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "peters_eyes".
 *
 * @property int $id
 * @property string|null $coords
 * @property int|null $prize
 * @property int|null $winner_id
 * @property string|null $win_at
 * @property int|null $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $winner
 * @property ImageModel $image
 */
class PetersEye extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;

    public $image_field;
    public $coords_field;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'peters_eyes';
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
            [
                'class' => GeometryBehavior::class,
                'type' => GeometryBehavior::GEOMETRY_POINT,
                'attribute' => 'coords',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prize', 'winner_id', 'status'], 'default', 'value' => null],
            [['prize', 'winner_id', 'status'], 'integer'],
            [['image_field'], 'file', 'extensions' => ['png', 'jpg', 'jpeg', 'webp'], 'maxSize' => 1024*1024*10],
            [['win_at', 'coords_field'], 'safe'],
        ];
    }

    /**
     * @return bool
     */
    public function beforeValidate()
    {
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
            'coords' => 'Координаты',
            'coords_field' => 'Координаты',
            'prize' => 'Приз',
            'image_field' => 'Изображение',
            'winner_id' => 'Победитель',
            'win_at' => 'Дата победы',
            'status' => 'Статус',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWinner()
    {
        return $this->hasOne(User::className(), ['user_id' => 'winner_id']);
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

    /**
     * @return array|\yii\db\ActiveRecord|null
     */
    public static function getActive()
    {
        return PetersEye::find()->where(['status' => PetersEye::STATUS_ACTIVE])->one();
    }
}
