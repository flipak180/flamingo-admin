<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "compilations".
 *
 * @property int $compilation_id
 * @property string $title
 * @property string|null $image
 * @property string|null $description
 * @property bool|null $in_trash
 * @property string $created_at
 * @property string $updated_at
 *
 * @property CompilationPlace[] $compilationPlaces
 * @property Place[] $places
 */
class Compilation extends \yii\db\ActiveRecord
{
    public $image_field;
    public $places_field;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'compilations';
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
            [['description'], 'string'],
            [['in_trash'], 'boolean'],
            [['title', 'image'], 'string', 'max' => 255],
            [['image_field'], 'file', 'extensions' => ['png', 'jpg', 'jpeg'], 'maxSize' => 1024*1024*10],
            ['places_field', 'safe']
        ];
    }

    /**
     * @return void
     */
    public function afterFind()
    {
        parent::afterFind();
        foreach ($this->places as $place) {
            $this->places_field[] = $place->place_id;
        }
    }

    /**
     * @param $insert
     * @param $changedAttributes
     * @return void
     */
    public function afterSave($insert, $changedAttributes)
    {
        $this->handlePlaces();
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'compilation_id' => 'ID',
            'title' => 'Название',
            'image' => 'Изображение',
            'image_field' => 'Изображение',
            'description' => 'Описание',
            'places_field' => 'Места',
            'in_trash' => 'В корзине',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompilationPlaces()
    {
        return $this->hasMany(CompilationPlace::className(), ['compilation_id' => 'compilation_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlaces()
    {
        return $this->hasMany(Place::className(), ['place_id' => 'place_id'])
            ->via('compilationPlaces');
    }

    /**
     * @return void
     * @throws \yii\db\Exception
     * @throws \yii\db\StaleObjectException
     */
    public function handlePlaces() {
        if (!is_array($this->places_field)) {
            return;
        }

        /** @var Place[] $currentPlaces */
        $currentPlaces = $this->getPlaces()->all();

        foreach ($currentPlaces as $currentPlace) {
            if (!in_array($currentPlace->title, $this->places_field)) {
                $this->unlink('places', $currentPlace, true);
            }
        }

        foreach ($this->places_field as $placeId) {
            $place = Place::findOne($placeId);
            $this->link('places', $place);
        }
    }
}
