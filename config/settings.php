<?php

return [

     /*
     |--------------------------------------------------------------------------
     | Settings Table
     |--------------------------------------------------------------------------
     | The name of the table where settings will be store
     */
    'table' => 'settings',

    /*
     |--------------------------------------------------------------------------
     | Settings paths
     |--------------------------------------------------------------------------
     | The path(s) to the directory will settings classes will be stored
     | This can either be a string or an array of strings
     | These settings will be translated to DB records by running the
     | settings:sync command
     */
    'paths' => []
];
