<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "visits".
 *
 * @property int $id
 * @property int $place_id
 * @property int $user_id
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Place $place
 * @property User $user
 */
class Visit extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'visits';
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
            [['place_id', 'user_id'], 'required'],
            [['place_id', 'user_id'], 'integer'],
            [['place_id'], 'canVisit'],
        ];
    }

    /**
     * @param $attribute
     * @param $params
     * @param $validator
     * @return void
     */
    public function canVisit($attribute, $params, $validator)
    {
        /** @var Visit $lastVisit */
        $lastVisit = Visit::find()
            ->where([
                'place_id' => $this->place_id,
                'user_id' => $this->user_id,
            ])
            ->andWhere(['>=', 'created_at', date("Y-m-d H:i:s", strtotime('-1 minute'))])
            ->one();

        if ($lastVisit) {
            $this->addError($attribute, 'Вы уже отметились.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'place_id' => 'Место',
            'user_id' => 'Пользователь',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlace()
    {
        return $this->hasOne(Place::className(), ['id' => 'place_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
