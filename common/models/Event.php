<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\web\UploadedFile;

/**
 * This is the model class for table "events".
 *
 * @property int $event_id
 * @property string $title
 * @property string|null $subtitle
 * @property string|null $image
 * @property int|null $place_id
 * @property string|null $description
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Place $place
 */
class Event extends \yii\db\ActiveRecord
{
    public $image_field;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'events';
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
            [['place_id'], 'default', 'value' => null],
            [['place_id'], 'integer'],
            [['description'], 'string'],
            [['title', 'subtitle', 'image'], 'string', 'max' => 255],
            [['image_field'], 'file', 'extensions' => ['png', 'jpg'], 'maxSize' => 1024*1024],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'event_id' => 'ID',
            'title' => 'Название',
            'subtitle' => 'Подзаголовок',
            'image' => 'Изображение',
            'image_field' => 'Изображение',
            'place_id' => 'Место',
            'description' => 'Описание',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @param $insert
     * @param $changedAttributes
     * @return void
     */
    public function afterSave($insert, $changedAttributes)
    {
        $this->uploadImage();
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlace()
    {
        return $this->hasOne(Place::className(), ['place_id' => 'place_id']);
    }

    /**
     *
     */
    public function uploadImage()
    {
        if ($image = UploadedFile::getInstance($this, 'image_field')) {
            $image_path = '/upload/images/event_'.$this->event_id.'.'. $image->extension;
            $image->saveAs(Yii::getAlias('@frontend_web').$image_path);
            $this->image = $image_path;
            $this->save(false);
        }
    }

    /**
     *
     */
    public function deleteImage()
    {
        if ($this->image && file_exists(Yii::getAlias('@frontend_web').$this->image)) {
            unlink(Yii::getAlias('@frontend_web').$this->image);
            $this->image = null;
            $this->save(false);
        }
    }
}
