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
    public function actionUpdateProfile()
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;

        $name = Yii::$app->request->post('name');
        if ($name) {
            $user->name = Yii::$app->request->post('name');
            if (!$user->save()) {
                $errors = $user->getFirstErrors();
                return $this->error(400, reset($errors));
            }
        }

        $image = UploadedFile::getInstanceByName('avatar');
        if ($image) {
            if (!$user->uploadImage($image)) {
                $errors = $user->getFirstErrors();
                return $this->error(400, reset($errors));
            }
        }

        return $this->response('ok');
    }
}
