<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    protected $backendNamespace = 'App\Http\Controllers\Backend';

    protected $apiNamespace = 'App\Http\Controllers\Api';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $httpHost = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';

        $prefix = explode('.', $httpHost)[0];

        $routePrefixes = config('app.route_prefixes');

        if ($routePrefixes['web'] == $prefix) {
            $this->mapWebRoutes();
        } else if ($routePrefixes['api'] == $prefix) {
            $this->mapApiRoutes();
        } else if ($routePrefixes['backend'] == $prefix) {
            $this->mapBackendRoutes();
        }
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::middleware('api')
             ->namespace($this->apiNamespace)
             ->group(base_path('routes/api.php'));
    }

    protected function mapBackendRoutes()
    {
        Route::middleware('web', 'auth.backend', 'viewLocation')
            ->namespace($this->backendNamespace)
            ->group(base_path('routes/backend.php'));
    }
}
