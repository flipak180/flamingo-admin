<?php

namespace common\models\Compilations;

use common\models\Places\Place;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "compilation_places".
 *
 * @property int $compilation_place_id
 * @property int $compilation_id
 * @property int $place_id
 * @property string $created_at
 * @property string $updated_at
 */
class CompilationPlace extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'compilation_places';
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
            [['compilation_id', 'place_id'], 'required'],
            [['compilation_id', 'place_id'], 'default', 'value' => null],
            [['compilation_id', 'place_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'compilation_place_id' => 'Compilation Place ID',
            'compilation_id' => 'Compilation ID',
            'place_id' => 'Place ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompilation()
    {
        return $this->hasOne(Compilation::className(), ['compilation_id' => 'compilation_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlace()
    {
        return $this->hasOne(Place::className(), ['place_id' => 'place_id']);
    }
}
