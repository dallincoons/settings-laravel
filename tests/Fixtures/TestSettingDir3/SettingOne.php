<?php

namespace SMST\SettingsLaravel\Tests\Fixtures\TestSettingDir3;

use SMST\Settings\Setting;

class SettingOne extends Setting
{
    public function defaultValue()
    {
        return 'different value';
    }
}
