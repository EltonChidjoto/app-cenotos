<?php

namespace App\Providers;

use App\Services\TenantService;
use App\Support\FallbackSessionHandler;
use Illuminate\Session\CacheBasedSessionHandler;
use Illuminate\Session\DatabaseSessionHandler;
use Illuminate\Session\SessionManager;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(TenantService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->resolving('session', function (SessionManager $manager): void {
            $manager->extend('redis', function ($app): FallbackSessionHandler {
                $config = $app['config']->get('session');
                $cache = clone $app->make('cache')->store($config['store'] ?: 'redis');

                if ($config['connection']) {
                    $cache->getStore()->setConnection($config['connection']);
                }

                return new FallbackSessionHandler(
                    new CacheBasedSessionHandler($cache, $config['lifetime']),
                    new DatabaseSessionHandler(
                        $app->make('db')->connection($config['connection']),
                        $config['table'],
                        $config['lifetime'],
                        $app,
                    ),
                );
            });
        });
    }
}
