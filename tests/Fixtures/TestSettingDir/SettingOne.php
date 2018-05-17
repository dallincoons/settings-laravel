<?php

namespace SMST\SettingsLaravel\Tests\Fixtures\TestSettingDir;

use SMST\Settings\Setting;

class SettingOne extends Setting
{
    const KEY = 'setting_one';
    const VALUE = 'test';

    public function defaultValue()
    {
        return self::VALUE;
    }
}
