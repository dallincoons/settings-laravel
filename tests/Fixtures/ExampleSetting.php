<?php

namespace SMST\SettingsLaravel\Tests\Fixtures;

use SMST\Settings\Setting;

class ExampleSetting extends Setting
{
    public function defaultValue()
    {
        return 'test';
    }
}
