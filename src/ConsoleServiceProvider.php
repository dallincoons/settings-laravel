<?php

namespace SMST\SettingsLaravel;

use SMST\SettingsLaravel\Console\Commands\SettingsSync;

class ConsoleServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $commands = [
        SettingsSync::class
    ];

    public function register()
    {
        $this->commands($this->commands);
    }
}
