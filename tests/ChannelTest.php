<?php

namespace LaravelNotification\DyvmsIvrcall\Test;

use AlibabaCloud\SDK\Dyvmsapi\V20170525\Dyvmsapi;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use LaravelNotification\DyvmsIvrcall\IvrcallChannel;
use Mockery;

class ChannelTest extends TestCase
{
    protected $dyvmsapi;
    protected $channel;

    public function setUp(): void
    {
        parent::setUp();
        $this->dyvmsapi = Mockery::mock(Dyvmsapi::class);
        $this->channel = new IvrcallChannel($this->dyvmsapi);
    }

    public function test_it_can_be_instantiated()
    {
        $this->assertInstanceOf(Dyvmsapi::class, $this->dyvmsapi);
        $this->assertInstanceOf(IvrcallChannel::class, $this->channel);
    }

    public function test_it_can_send_notification()
    {
        $this->dyvmsapi->expects('IvrCall')
            ->once()
            ->andReturn(200);
        $this->channel->send(new TestNotifiable, new TestNotification);
    }
}

class TestNotification extends Notification
{
    public function toIvrcall($notifiable): array
    {
        return [
            'calledShowNumber' => '037999999999',
            'calledNumber' => $notifiable->routeNotificationForIvrcall(),
            'startCode' => 'TTS_12345678',
            'menuKeyMap' => [
                [
                    'key' => 1,
                    'ttsParams' => [
                        'name' => 'xxx',
                        'code' => '123',
                    ],
                    'code' => 'TTS_12345',
                ],
            ],
        ];
    }
}

class TestNotifiable
{
    use Notifiable;

    public function routeNotificationForIvrcall($notification = null): string
    {
        return '13888888888';
    }
}
