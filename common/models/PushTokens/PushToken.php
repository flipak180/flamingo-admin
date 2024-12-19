<?php

namespace common\models\PushTokens;

use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "push_tokens".
 *
 * @property int $id
 * @property string $token
 * @property int|null $user_id
 * @property string $created_at
 * @property string $updated_at
 */
class PushToken extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'push_tokens';
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
            [['token'], 'required'],
            [['user_id'], 'default', 'value' => null],
            [['user_id'], 'integer'],
            [['token'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'token' => 'Token',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
