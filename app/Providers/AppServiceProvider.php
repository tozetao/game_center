<?php

namespace App\Providers;

use EasyWeChat\Factory;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (env('APP_ENV') == 'local') {
            \DB::listen(function($query){
                Log::info($query->sql, $query->bindings);
            });

            $uri = request()->getRequestUri();
            Log::info('uri: ' . $uri);
        }

        Resource::withoutWrapping();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('EasyWeChat\Payment\Application', function() {
            $config = [
                'app_id' => env('WECHAT_PAYMENT_APPID', ''),
                'key'    => env('WECHAT_PAYMENT_KEY', ''),
                'mch_id' => env('WECHAT_PAYMENT_MCH_ID', ''),
                'cert_path' => env('WECHAT_PAYMENT_CERT_PATH', ''),
                'key_path' => env('WECHAT_PAYMENT_KEY_PATH', ''),
                'sandbox'  => env('WECHAT_PAYMENT_SANDBOX', true)
            ];
            return Factory::payment($config);
        });

        $this->app->singleton('EasyWeChant', function() {
            $config = [
                'app_id' => env('WECHAT_OFFICIAL_ACCOUNT_APPID', ''),
                'secret' => env('WECHAT_OFFICIAL_ACCOUNT_SECRET', '')
            ];
            return Factory::officialAccount($config);
        });
    }
}
