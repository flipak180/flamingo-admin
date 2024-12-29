<?php

namespace api\controllers;

use api\models\Places\PlaceApiItem;
use common\components\Helper;
use common\models\User;
use common\models\UserPlace;
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

        //Yii::$app->sms->sendSMS($phone, '');

        $phone = User::encryptPhone($phone);
        $user = User::findOne(['phone' => $phone]);
        if (!$user) {
            return $this->error(400, 'Номер телефона не зарегистрирован.');
//            $user = new User();
//            $user->name = User::DEFAULT_NAME;
//            $user->phone = $phone;
//            if (!$user->save()) {
//                return $this->error(500, 'Произошла ошибка.');
//            }
        }

        return $this->response([
            'token' => $user->phone,
        ]);
    }

    /**
     * @return array
     */
    public function actionGetProfile()
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;

        return $this->response([
            'name' => $user->name,
            'avatar' => $user->getAvatarPreview(),
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
            $user->name = $name;
        }

        $image = UploadedFile::getInstanceByName('avatar');
        if ($image && !$user->uploadImage($image)) {
            return $this->error(400, 'Аватар не обновлен');
        }

        return $this->response([
            'name' => $user->name,
            'avatar' => $user->getAvatarPreview(),
        ]);
    }

    /**
     * @return array
     */
    public function actionGetRatedPlaces()
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;
        $result = [];
        foreach ($user->rates as $rate) {
            $result[] = PlaceApiItem::from($rate->place);
        }
        return $this->response($result);
    }

    /**
     * @return array
     */
    public function actionGetPlaces()
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;
        $result = [];
        foreach ($user->places as $userPlace) {
            $result[] = PlaceApiItem::from($userPlace->place);
        }
        return $this->response($result);
    }

    /**
     * @return array
     */
    public function actionPlace()
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;

        $place_id = Yii::$app->request->post('place_id');
        $status = Yii::$app->request->post('status');

        $userPlace = UserPlace::findOne(['place_id' => $place_id, 'user_id' => $user->user_id]);
        if (!$userPlace) {
            $userPlace = new UserPlace();
            $userPlace->user_id = $user->user_id;
            $userPlace->place_id = $place_id;
        }
        $userPlace->status = $status;
        if (!$userPlace->save()) {
            $errors = $userPlace->getFirstErrors();
            return $this->error(400, reset($errors));
        }

        return $this->response(true);
    }

    /**
     * @return array
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDeleteAccount()
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;
        return $this->response($user->delete());
    }
}
