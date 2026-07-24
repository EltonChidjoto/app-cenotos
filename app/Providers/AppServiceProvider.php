<?php

namespace App\Providers;

use App\Services\TenantService;
use App\Support\FallbackSessionHandler;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Session\CacheBasedSessionHandler;
use Illuminate\Session\DatabaseSessionHandler;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

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
        RateLimiter::for('login', function (Request $request): array {
            $username = Str::lower(trim((string) $request->input('username')));

            return [
                Limit::perMinute(5)->by($username.'|'.$request->ip()),
                Limit::perMinute(30)->by('ip|'.$request->ip()),
            ];
        });

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
