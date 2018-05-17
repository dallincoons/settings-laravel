<?php

namespace SMST\SettingsLaravel\Macros;

class ArrMacros
{
    /**
     * Adapted from the Illuminate 'first' helper
     * returns the return value of the callback
     */
    public function mapFirst()
    {
        return function ($array, callable $callback, $default = null) {
            foreach ($array as $key => $value) {
                if ($result = call_user_func($callback, $value, $key)) {
                    return $result;
                }
            }

            return value($default);
        };
    }
}
