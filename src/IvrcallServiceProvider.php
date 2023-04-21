<?php

namespace LaravelNotification\DyvmsIvrcall;

use AlibabaCloud\SDK\Dyvmsapi\V20170525\Dyvmsapi;
use Darabonba\OpenApi\Models\Config;
use Illuminate\Support\ServiceProvider;

class IvrcallServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->when(IvrcallChannel::class)
            ->needs(Dyvmsapi::class)
            ->give(function () {
                $config = new Config(config('services.dyvms'));

                return new Dyvmsapi($config);
            });
    }
}
