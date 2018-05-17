<?php

namespace SMST\SettingsLaravel;

use Illuminate\Support\Arr;
use SMST\SettingsLaravel\Macros\ArrMacros;

class MacroServiceProvider extends ServiceProvider
{
    public function register()
    {
        Arr::mixin(new ArrMacros());
    }
}
