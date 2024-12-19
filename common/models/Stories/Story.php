<?php

namespace common\models\Stories;

use common\behaviors\ImageBehavior;
use common\models\ImageModel;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "stories".
 *
 * @property int $id
 * @property string $title
 * @property string|null $link
 * @property int|null $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property StoryView[] $views
 * @property ImageModel $image
 */
class Story extends \yii\db\ActiveRecord
{
    public $image_field;

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stories';
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
     * @param $insert
     * @param $changedAttributes
     * @return void
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            Yii::$app->push->sendAll('Опачки!', 'Новая история - бегом смотреть 🦩');
        }
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['status'], 'default', 'value' => null],
            [['status'], 'integer'],
            [['title', 'link'], 'string', 'max' => 255],
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
            'link' => 'Ссылка',
            'status' => 'Статус',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getViews()
    {
        return $this->hasMany(StoryView::className(), ['story_id' => 'id']);
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
