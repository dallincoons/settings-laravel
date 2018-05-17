<?php

namespace SMST\SettingsLaravel\Console\Commands;

use Illuminate\Console\Command;
use SMST\SettingsLaravel\Services\SettingsSyncer;

class SettingsSync extends Command
{
    const NAME = 'settings:sync';

    protected $name = self::NAME;

    public function handle(SettingsSyncer $syncer)
    {
        $settings = $syncer->settings();

        $settingAdded = $syncer->attachSettings($settings);
        $settingsRemoved = $syncer->detachSettings($settings);

        foreach ($settingAdded as $added) {
            $this->line('Setting added: ' . $added);
        }

        foreach ($settingsRemoved as $removed) {
            $this->line('Setting removed: ' . $removed);
        }
    }
}
