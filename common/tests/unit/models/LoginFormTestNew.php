<?php

namespace common\tests\unit\models;

use common\models\LoginForm;
use PHPUnit\Framework\TestCase;
use Yii;

final class LoginFormTestNew extends TestCase
{
    public function testLoginNoUser()
    {
        $model = new LoginForm([
            'username' => 'not_existing_username',
            'password' => 'not_existing_password',
        ]);

        echo Yii::$app ? 'fgdoihgdfgiuo' : '234323232444';

        $this->assertFalse($model->login());
        $this->assertTrue(Yii::$app->user->isGuest);
    }
}
