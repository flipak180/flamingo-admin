<?php

namespace common\behaviors;

use common\models\ImageModel;
use Imagine\Image\Box;
use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\imagine\Image;
use yii\web\UploadedFile;

class ImageBehavior extends Behavior
{
    /**
     * @var ActiveRecord the owner of this behavior
     */
    public $owner;

    /**
     * @var
     */
    public $attribute;

    /**
     * @return void
     */
    public function init()
    {
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function events()
    {

        $events = [
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
            ActiveRecord::EVENT_AFTER_FIND => 'afterFind',
        ];

        return $events;

    }

    /**
     * @return bool
     */
    public function beforeSave()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function afterSave()
    {
        foreach (UploadedFile::getInstances($this->owner, $this->attribute) as $image) {
            Yii::info($image);


            $classPath = get_class($this->owner);
            $classPathArr = explode('\\', $classPath);
            $className = end($classPathArr);

            Yii::info($classPath);
            Yii::info($className);

            do {
                $image_path = '/upload/images/'.strtolower($className).'_'.md5(rand()).'.'.$image->extension;
                $full_path = Yii::getAlias('@frontend_web').$image_path;
            } while (file_exists($full_path));

            $image->saveAs($full_path);

            Image::frame($full_path, 0)
                ->thumbnail(new Box(800, 800))
                ->save($full_path, ['quality' => 100]);

            $newImage = new ImageModel();
            $newImage->path = $image_path;
            $newImage->model = $className;
            $newImage->model_id = $this->owner->primaryKey;
            $newImage->save();
        }

        return true;
    }

    /**
     * @return bool
     */
    public function afterFind()
    {
        return true;
    }

}
