<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "tickets".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $type
 * @property string|null $message
 * @property string $created_at
 * @property string $updated_at
 */
class Ticket extends \yii\db\ActiveRecord
{
    const TYPE_BUG = 1;
    const TYPE_INCORRECT = 2;
    const TYPE_SUGGESTION = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tickets';
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
            [['user_id', 'type'], 'default', 'value' => null],
            [['user_id', 'type'], 'integer'],
            [['message'], 'string'],
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
            'type' => 'Тип',
            'message' => 'Сообщение',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return array
     */
    public static function getTypesList() {
        return [
            self::TYPE_BUG => 'Баг',
            self::TYPE_INCORRECT => 'Неточность',
            self::TYPE_SUGGESTION => 'Предложение',
        ];
    }

    /**
     * @return mixed
     */
    public function getTypeTitle() {
        return self::getTypesList()[$this->type] ?? '-';
    }
}
