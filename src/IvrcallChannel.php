<?php

namespace LaravelNotification\DyvmsIvrcall;

use AlibabaCloud\SDK\Dyvmsapi\V20170525\Dyvmsapi;
use AlibabaCloud\SDK\Dyvmsapi\V20170525\Models\IvrCallRequest;
use AlibabaCloud\SDK\Dyvmsapi\V20170525\Models\IvrCallRequest\menuKeyMap;
use Exception;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Arr;
use RuntimeException;

class IvrcallChannel
{
    public function __construct(
        protected Dyvmsapi $client
    ) {}

    public function send($notifiable, Notification $notification)
    {
        $request = $this->buildRequest($notifiable, $notification);

        try {
            $this->client->ivrCall($request);
        } catch (Exception $e) {
            throw $e;
        }
    }

    protected function getData($notifiable, Notification $notification)
    {
        if (method_exists($notification, 'toIvrcall')) {
            return $notification->toIvrcall($notifiable);
        }

        if (method_exists($notification, 'toArray')) {
            return $notification->toArray($notifiable);
        }

        throw new RuntimeException('消息通知类没有定义 toArray 方法');
    }

    protected function buildRequest($notifiable, Notification $notification)
    {
        $message = $this->getData($notifiable, $notification);
        $message['menuKeyMap'] = Arr::map($message['menuKeyMap'], function ($keyMap) {
            return new menuKeyMap($keyMap);
        });

        return new IvrCallRequest($message);
    }
}
