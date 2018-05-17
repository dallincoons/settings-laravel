<?php

namespace SMST\SettingsLaravel\Tests;

use SMST\Settings\Repository\SettingsRepository;
use SMST\SettingsLaravel\Tests\Fixtures\Repositories\FakeSettingsRepository;
use SMST\Testing\AssertsExceptions;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use AssertsExceptions;

    public function setUp()
    {
        parent::setUp();

        app()->singleton(SettingsRepository::class, FakeSettingsRepository::class);

        $this->app->setBasePath(__DIR__);
    }

    protected function getPackageProviders($app)
    {
        return [
            \SMST\SettingsLaravel\ServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    protected function getPackageAliases($app)
    {
        return [
            'Settings' => \SMST\SettingsLaravel\Facades\Settings::class
        ];
    }

    /**
     * @param string|array $paths
     */
    protected function setConfig($paths)
    {
        config(['settings.paths' => $paths]);
    }
}
