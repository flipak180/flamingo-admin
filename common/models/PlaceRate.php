<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "rates".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $place_id
 * @property int|null $rate
 * @property string $created_at
 * @property string $updated_at
 */
class PlaceRate extends \yii\db\ActiveRecord
{
    const DISLIKE = 1;
    const MEH = 2;
    const LIKE = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rates';
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
            [['user_id', 'place_id', 'rate'], 'default', 'value' => null],
            [['user_id', 'place_id', 'rate'], 'integer'],
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
            'place_id' => 'Place ID',
            'rate' => 'Rate',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
