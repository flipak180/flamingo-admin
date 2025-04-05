<?php

namespace api\controllers;

use api\models\Places\PlaceApiItem;
use common\components\Helper;
use common\models\User;
use common\models\UserPlace;
use common\models\UserProgress\UserProgress;
use OpenApi\Attributes as OA;
use Yii;
use yii\db\Exception;
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

    #[OA\Post(
        path: '/api/users/auth',
        requestBody: new OA\RequestBody(content: [

        ]),
        tags: ['users']
    )]
    #[OA\Response(response: '200', description: 'OK')]
    public function actionAuth()
    {
        $phone = Yii::$app->request->post('phone');
        if (!$phone) {
            return $this->error(400, 'Не указан номер телефона.');
        }

        $phone = Helper::clearPhone($phone);

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

    #[OA\Post(
        path: '/api/users/get-profile',
        tags: ['users'],
    )]
    #[OA\Response(response: '200', description: 'OK')]
    public function actionGetProfile()
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;

        return $this->response([
            'name' => $user->name,
            'avatar' => $user->getAvatarPreview(),
        ]);
    }

    #[OA\Post(
        path: '/api/users/update-profile',
        tags: ['users'],
        parameters: [
            new OA\Parameter(name: 'name', description: 'Name', in: 'query'),
            new OA\Parameter(name: 'avatar', description: 'Avatar', in: 'query'),
        ]
    )]
    #[OA\Response(response: '200', description: 'OK')]
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

    #[OA\Post(
        path: '/api/users/get-rated-places',
        tags: ['users'],
    )]
    #[OA\Response(response: '200', description: 'OK')]
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

    #[OA\Post(
        path: '/api/users/get-places',
        tags: ['users'],
    )]
    #[OA\Response(response: '200', description: 'OK')]
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

    #[OA\Post(
        path: '/api/users/place',
        tags: ['users'],
        parameters: [
            new OA\Parameter(name: 'place_id', description: 'Place ID', in: 'query', required: true),
            new OA\Parameter(name: 'status', description: 'Status', in: 'query', required: true),
        ]
    )]
    #[OA\Response(response: '200', description: 'OK')]
    public function actionPlace()
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;

        $place_id = Yii::$app->request->post('place_id');
        $status = Yii::$app->request->post('status');

        if (!$place_id || !$status) {
            return $this->error(400);
        }

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
     * @throws Exception
     */
    #[OA\Post(
        path: '/api/users/get-progress',
        tags: ['users'],
    )]
    #[OA\Response(response: '200', description: 'OK')]
    public function actionGetProgress()
    {
        return $this->response(UserProgress::byCategories());
    }

    #[OA\Post(
        path: '/api/users/delete-account',
        tags: ['users'],
    )]
    #[OA\Response(response: '200', description: 'OK')]
    public function actionDeleteAccount()
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;
        return $this->response($user->delete());
    }
}
