<?php

namespace SMST\SettingsLaravel;

use SMST\Settings\Repository\SettingsRepository;
use SMST\SettingsLaravel\Repositories\SettingsRepository as LaravelSettingsRepository;

/**
 * @codeCoverageIgnore
 */
class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->loadConfiguration();

        $this->loadMigrationsFrom(
            __DIR__ . '/../database/migrations'
        );

        $this->app->bind(SettingsRepository::class, LaravelSettingsRepository::class);
        $this->app->bind('settings', function () {
            return new Settings(app(SettingsRepository::class));
        });

        $this->registerServiceProviders();
    }

    public function loadConfiguration()
    {
        $configPath = __DIR__ . '/../config/settings.php';

        $this->mergeConfigFrom($configPath, 'settings');
    }

    public function registerServiceProviders()
    {
        $this->app->register(ConsoleServiceProvider::class);
        $this->app->register(MacroServiceProvider::class);
    }
}
