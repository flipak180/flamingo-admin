<?php

namespace app\controllers;

use common\components\Helper;
use common\models\User;
use himiklab\thumbnail\EasyThumbnailImage;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\web\UploadedFile;

class UserController extends BaseApiController
{
    public $modelClass = 'common\models\User';

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'except' => ['auth'],
        ];
        return $behaviors;
    }

    /**
     * @return array
     */
    public function actionAuth()
    {
        $phone = Yii::$app->request->post('phone');
        $phone = Helper::clearPhone($phone);
        if (!$phone) {
            return $this->error(400, 'Не указан номер телефона.');
        }

        $phone = User::encryptPhone($phone);
        $user = User::findOne(['phone' => $phone]);
        if (!$user) {
            $user = new User();
            $user->name = User::DEFAULT_NAME;
            $user->phone = $phone;
            if (!$user->save()) {
                return $this->error(500, 'Произошла ошибка.');
            }
        }

        $avatar = $user->avatar
            ? EasyThumbnailImage::thumbnailFileUrl(Yii::getAlias('@frontend_web').$user->avatar, 200, 200, EasyThumbnailImage::THUMBNAIL_OUTBOUND, 100)
            : '';

        return $this->response([
            'token' => $user->phone,
            'name' => $user->name,
            'avatar' => $avatar,
        ]);
    }

    /**
     * @return array
     */
    public function actionUpdateName()
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;

        $name = Yii::$app->request->post('name');
        if (!$name) {
            return $this->error(400, 'Имя обязательно для заполнения');
        }

        $user->name = $name;
        if (!$user->save()) {
            $errors = $user->getFirstErrors();
            return $this->error(400, reset($errors));
        }

        return $this->response($user->name);
    }

    /**
     * @return array
     */
    public function actionUpdateAvatar()
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;

        $image = UploadedFile::getInstanceByName('avatar');
        if (!$image) {
            return $this->error(400, 'Изображение не загружено');
        }

        if (!$user->uploadImage($image)) {
            $errors = $user->getFirstErrors();
            return $this->error(400, reset($errors));
        }

        return $this->response($user->avatar);
    }

    /**
     * @return array
     * @throws \himiklab\thumbnail\FileNotFoundException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function actionGetRatedPlaces()
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;

        $result = [];

        foreach ($user->rates as $rate) {
            $place = $rate->place;

            $images = [];
            $smallImages = [];
            foreach ($place->images as $image) {
                $images[] = EasyThumbnailImage::thumbnailFileUrl(Yii::getAlias('@frontend_web').$image->path, 344, 344, EasyThumbnailImage::THUMBNAIL_OUTBOUND, 100);
                $smallImages[] = EasyThumbnailImage::thumbnailFileUrl(Yii::getAlias('@frontend_web').$image->path, 150, 150, EasyThumbnailImage::THUMBNAIL_OUTBOUND, 100);
            }

            $tags = [];
            foreach ($place->tags as $tag) {
                $tags[] = $tag->title;
            }

            $result[] = [
                'id' => $place->place_id,
                'title' => $place->title,
                'sort_title' => $place->sort_title,
                'image' => count($images) ? $images[0] : '',
                'images' => $images,
                'small_images' => $smallImages,
                'small_image' => count($smallImages) ? $smallImages[0] : '',
                'tags' => $tags,
                'coords' => $place->coords,
                'status' => 1,
                'rate' => $rate->rate,
            ];
        }

        return $this->response($result);
    }
}
