<?php
namespace common\components;

use common\models\PushTokens\PushTokensSearch;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Yii;
use yii\base\Component;

class PushNotificationComponent extends Component {

    const SERVICE_ACCOUNT = 'flamingo-1b748-firebase-adminsdk-ptthr-9ff2a85215.json';

    /**
     * @var Messaging
     */
    private $messagingClient;

    public function init()
    {
        $factory = (new Factory)->withServiceAccount(dirname(Yii::getAlias('@vendor')) . '/secrets/' . self::SERVICE_ACCOUNT);
        $this->messagingClient = $factory->createMessaging();
        parent::init();
    }

    /**
     * @param $deviceToken
     * @param $title
     * @param $body
     * @param array $extra
     * @return bool
     * @throws FirebaseException
     * @throws MessagingException
     */
    public function send($deviceToken, $title, $body, $extra = []) {
        $message = CloudMessage::new()
            ->withNotification(Notification::create($title, $body))
            ->withData($extra)
            ->toToken($deviceToken);

        $response = $this->messagingClient->send($message);
        return isset($response['name']);
    }

    /**
     * @param $title
     * @param $body
     * @param $deviceTokens
     * @return void
     * @throws FirebaseException
     * @throws MessagingException
     */
    public function sendAll($title, $body, $deviceTokens = null)
    {
        if (!is_array($deviceTokens)) {
            $deviceTokens = PushTokensSearch::getAllDeviceTokens();
        }
        $message = CloudMessage::new()
            ->withNotification(Notification::create($title, $body));

        $sendReport = $this->messagingClient->sendMulticast($message, $deviceTokens);
    }

}
