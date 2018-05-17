<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    public function up()
    {
        \Schema::create(config('settings.table'), function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('key')->unique();
            $table->string('value');

            $table->timestamps();
        });
    }

    public function down()
    {
        \Schema::dropIfExists(config('settings.table'));
    }
}
