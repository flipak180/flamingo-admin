<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "peters_eye_users".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $peters_eye_id
 * @property bool|null $is_winner
 * @property string $created_at
 * @property string $updated_at
 *
 * @property PetersEye $petersEye
 * @property User $user
 */
class PetersEyeUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'peters_eye_users';
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
            [['user_id', 'peters_eye_id'], 'default', 'value' => null],
            [['user_id', 'peters_eye_id'], 'integer'],
            [['is_winner'], 'boolean'],
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
            'peters_eye_id' => 'Peters Eye ID',
            'is_winner' => 'Is Winner',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
    public function getPetersEye()
    {
        return $this->hasOne(PetersEye::className(), ['peters_eye_id' => 'id']);
    }
}
